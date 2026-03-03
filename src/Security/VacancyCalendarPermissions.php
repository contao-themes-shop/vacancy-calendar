<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Security;

final class VacancyCalendarPermissions
{
    public const USER_CAN_ACCESS_MODULE = 'contao_user.modules.cts_vacancy_calendar';

    public const USER_CAN_EDIT_VACANCY_CALENDAR = 'contao_user.vc_vacancy_calendar';

    public const USER_CAN_CREATE_VACANCY_CALENDAR = 'contao_user.vc_vacancy_calendar_permission.create';

    public const USER_CAN_DELETE_VACANCY_CALENDAR = 'contao_user.vc_vacancy_calendar_permission.delete';
}
