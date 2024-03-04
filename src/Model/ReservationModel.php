<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Model;

use Contao\Model;

/**
 * @property string $title
 * @property int    $begin
 * @property int    $end
 * @property string $note
 */
final class ReservationModel extends Model
{
    /** @var string */
    protected static $strTable = 'tl_cts_vacancy_calendar_reservation';
}
