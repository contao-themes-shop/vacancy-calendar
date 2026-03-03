<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\StringUtil;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Netzmacht\Contao\Toolkit\Dca\DcaManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;

use function in_array;
use function is_array;
use function serialize;

final class VacancyCalendarDcaListener
{
    public function __construct(
        private readonly Security $security,
        private readonly DcaManager $dcaManager,
        private readonly RequestStack $requestStack,
        private readonly Connection $connection,
    ) {
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    #[AsCallback(table: 'tl_cts_vacancy_calendar', target: 'config.onload')]
    public function onLoad(): void
    {
        $user = $this->security->getUser();

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        if (empty($user->vc_vacancy_calendar) || ! is_array($user->vc_vacancy_calendar)) {
            $root = [0];
        } else {
            $root = $user->vc_vacancy_calendar;
        }

        $definition = $this->dcaManager->getDefinition('tl_cts_vacancy_calendar');
        $definition->set('list/sorting/root', $root);
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    #[AsCallback(table: 'tl_cts_vacancy_calendar', target: 'config.oncreate')]
    public function onCreate(string $table, int $vacancyCalendarId): void
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $user = $this->security->getUser();

        // Set root IDs
        if (empty($user->news) || ! is_array($user->news)) {
            $root = [0];
        } else {
            $root = $user->news;
        }

        // The archive is enabled already
        if (in_array($vacancyCalendarId, $root)) {
            return;
        }

        $sessionBag = $this->requestStack->getSession()->getBag('contao_backend');
        $record     = $sessionBag->get('new_records');

        if (
            ! is_array($record['tl_cts_vacancy_calendar'])
            || ! in_array($vacancyCalendarId, $record['tl_cts_vacancy_calendar'])
        ) {
            return;
        }

        if ($user->inherit !== 'custom') {
            $queryBuilder = $this->connection->createQueryBuilder();
            $queryBuilder
                ->select('tug.id', 'tug.vc_vacancy_calendar', 'tug.vc_vacancy_calendar_permission')
                ->from('tl_user_group', 'tug')
                ->where($queryBuilder->expr()->in('tug.id', ':groupIds'))
                ->setParameter('groupIds', $user->groups, ArrayParameterType::INTEGER);

            $groups = $queryBuilder->executeQuery()->fetchAllAssociative();

            foreach ($groups as $group) {
                $permissions = StringUtil::deserialize($group['vc_vacancy_calendar_permission']);

                if (! is_array($permissions) || ! in_array('create', $permissions)) {
                    continue;
                }

                $vacancyCalendars   = StringUtil::deserialize($group['vc_vacancy_calendar'], true);
                $vacancyCalendars[] = $vacancyCalendarId;

                try {
                    $this->connection
                        ->update(
                            'tl_user_group',
                            ['vc_vacancy_calendar' => serialize($vacancyCalendars)],
                            ['id' => $group['id']],
                        );
                } catch (Throwable) {
                }
            }
        }

        if ($user->inherit !== 'group') {
            $queryBuilder
                ->select('tu.id', 'tu.vc_vacancy_calendar', 'tu.vc_vacancy_calendar_permission')
                ->from('tl_user', 'tu')
                ->where($queryBuilder->expr()->eq('tu.id', ':userId'))
                ->setParameter('userId', $user->id, ParameterType::INTEGER)
                ->setMaxResults(1);

            $userRecord = $queryBuilder->executeQuery()->fetchAssociative();

            $permissions = StringUtil::deserialize($userRecord['vc_vacancy_calendar_permission']);

            if (is_array($permissions) && in_array('create', $permissions)) {
                $vacancyCalendars   = StringUtil::deserialize($userRecord['vc_vacancy_calendar'], true);
                $vacancyCalendars[] = $vacancyCalendarId;

                $this->connection->update(
                    'tl_user',
                    ['vc_vacancy_calendar' => serialize($vacancyCalendars)],
                    ['id' => $userRecord['id']],
                );
            }
        }

        // Add the new element to the user object
        $root[]                    = $vacancyCalendarId;
        $user->vc_vacancy_calendar = $root;
    }
}
