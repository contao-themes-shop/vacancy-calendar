<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\Model;

use Contao\Model;

/**
 * @property string $title
 */
final class CalendarModel extends Model
{
    protected static $strTable = 'tl_cts_vacancy_calendar';
}
