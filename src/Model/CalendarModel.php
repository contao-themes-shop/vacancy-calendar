<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Model;

use Contao\Model;

/** @property string $title */
final class CalendarModel extends Model
{
    /** @var string */
    protected static $strTable = 'tl_cts_vacancy_calendar';
}
