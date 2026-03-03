<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use ContaoThemesShop\VacancyCalendar\Model\CalendarRepository;
use ContaoThemesShop\VacancyCalendar\Security\VacancyCalendarPermissions;
use Symfony\Bundle\SecurityBundle\Security;

use function is_array;

final class ModuleDcaListener
{
    public function __construct(
        private readonly Security $security,
        private readonly CalendarRepository $calendars,
    ) {
    }

    /** @return array<int, string> */
    #[AsCallback(table: 'tl_module', target: 'fields.vc_calendar.options')]
    public function onOptions(): array
    {
        $user = $this->security->getUser();

        if (! $this->security->isGranted('ROLE_ADMIN') && ! is_array($user->vc_vacancy_calendar)) {
            return [];
        }

        $calendars = $this->calendars->findAll();
        $options   = [];

        foreach ($calendars as $calendar) {
            if (
                $this->security->isGranted(
                    VacancyCalendarPermissions::USER_CAN_EDIT_VACANCY_CALENDAR,
                    $calendar->id,
                ) === false
            ) {
                continue;
            }

            $options[$calendar->id] = $calendar->title;
        }

        return $options;
    }
}
