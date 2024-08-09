<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Carbon;

use Carbon\Carbon;
use Contao\Config;
use DateTimeZone;

class CarbonLocalTimezone
{
    public static function create(): Carbon
    {
        $carbon = new Carbon();
        $carbon->setTimezone(Config::get('timeZone'));

        return $carbon;
    }

    public static function createFromDate($year = null, $month = null, $day = null, $timezone = null): Carbon
    {
        $carbon = Carbon::create($year, $month, $day, null, null, null, $timezone);
        $carbon->setTimezone(Config::get('timeZone'));

        return $carbon;
    }

    public static function createFromTimestamp(
        float|int|string $timestamp,
        DateTimeZone|string|int|null $timezone = null,
    ): Carbon {
        $carbon = Carbon::createFromTimestampUTC($timestamp);
        $carbon->setTimezone(Config::get('timeZone'));


        return $timezone === null ? $carbon : $carbon->setTimezone($timezone);
    }
}
