<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Security\Voter;

use Contao\CoreBundle\Security\DataContainer\CreateAction;
use Contao\CoreBundle\Security\DataContainer\DeleteAction;
use Contao\CoreBundle\Security\DataContainer\ReadAction;
use Contao\CoreBundle\Security\DataContainer\UpdateAction;
use Contao\CoreBundle\Security\Voter\DataContainer\AbstractDataContainerVoter;
use ContaoThemesShop\VacancyCalendar\Security\VacancyCalendarPermissions;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class VacancyCalendarAccessVoter extends AbstractDataContainerVoter
{
    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager)
    {
    }

    protected function getTable(): string
    {
        return 'tl_cts_vacancy_calendar';
    }

    protected function hasAccess(TokenInterface $token, CreateAction|DeleteAction|ReadAction|UpdateAction $action): bool
    {
        if (! $this->accessDecisionManager->decide($token, [VacancyCalendarPermissions::USER_CAN_ACCESS_MODULE])) {
            return false;
        }

        return match (true) {
            $action instanceof CreateAction => $this->accessDecisionManager->decide(
                $token,
                [VacancyCalendarPermissions::USER_CAN_CREATE_VACANCY_CALENDAR],
            ),
            $action instanceof ReadAction,
                $action instanceof UpdateAction => $this->accessDecisionManager->decide(
                    $token,
                    [VacancyCalendarPermissions::USER_CAN_EDIT_VACANCY_CALENDAR], $action->getCurrentId(),
                ),
            $action instanceof DeleteAction => $this->accessDecisionManager->decide(
                $token,
                [VacancyCalendarPermissions::USER_CAN_EDIT_VACANCY_CALENDAR],
                $action->getCurrentId(),
            )
                && $this->accessDecisionManager->decide(
                    $token,
                    [VacancyCalendarPermissions::USER_CAN_DELETE_VACANCY_CALENDAR],
                ),
        };
    }
}
