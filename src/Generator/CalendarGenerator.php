<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\Generator;

use Carbon\Carbon;
use Contao\Model\Collection;
use ContaoThemesShop\VacancyCalendar\Model\ReservationModel;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CalendarGenerator
{
    private $translator;

    private $reservations = [];

    private const dateFormat = 'Ymd';

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function generateMonth(int $month, bool $monthShort, bool $dayShort) : array
    {
        $currentMonth  = (new Carbon())->month;
        $month         = Carbon::createFromDate(null, ($currentMonth + $month), 1);
        $monthLabelKey = $monthShort ? 'MONTHS_SHORT' : 'MONTHS';
        $dayLabelKey   = $dayShort ? 'DAYS_SHORT' : 'DAYS';

        $data                   = [];
        $data['label']['month'] = $this->translator->trans(sprintf('%s.%s', $monthLabelKey, ($month->month - 1)), [],
            'contao_default');
        $data['label']['year']  = $month->year;

        for ($i = 1; $i <= 7; $i++) {
            if ($i === 7) {
                $data['days'][] = $this->translator->trans(sprintf('%s.%s', $dayLabelKey, 0), [],
                    'contao_default');
                continue;
            }

            $data['days'][] = $this->translator->trans(sprintf('%s.%s', $dayLabelKey, $i), [],
                'contao_default');
        }

        $week = 1;

        for ($i = 1; $i < $month->dayOfWeek; $i++) {
            $data['weeks'][$week][] = ['class' => 'empty'];
        }

        $day = clone $month;
        do {
            $dayBefore = clone $day->subDay();
            $dayAfter  = clone $day->addDay();

            if ($this->reservations[$day->format(self::dateFormat)] == '') {
                $class = 'vacant';
            } elseif ($this->reservations[$day->format(self::dateFormat)] == 1) {
                if ($this->reservations[$dayBefore->format(self::dateFormat)] <= 1) {
                    $class = 'begin';
                } elseif ($this->reservations[$dayAfter->format(self::dateFormat)] < 2) {
                    $class = 'end';
                } else {
                    $class = 'full';
                }

            } elseif ($this->reservations[$day->format(self::dateFormat)] > 1) {
                $class = 'full';
            }

            $data['weeks'][$week][] = ['class' => $class, 'day' => $day->day];

            if (count($data['weeks'][$week]) === 7) {
                $week++;
                $data['weeks'][$week] = [];
            }

            $day->addDay();
        } while ($day->lte($month->endOfMonth()));

        $remainingDays = count($data['weeks'][$week]);

        if ($remainingDays < 7 && $remainingDays > 0) {
            for ($i = 1; $i <= (7 - $remainingDays); $i++) {
                $data['weeks'][$week][] = ['class' => 'empty'];
            }
        }

        return $data;
    }

    public function addReservations(?Collection $reservations) : void
    {
        foreach ($reservations ?? [] as $reservation) {
            $this->addReservation($reservation);
        }

        ksort($this->reservations);
    }

    public function addReservation(ReservationModel $reservationModel) : void
    {
        $begin = Carbon::createFromTimestamp((int) $reservationModel->begin);
        $end   = Carbon::createFromTimestamp((int) $reservationModel->end);

        $this->reservations[$begin->format(self::dateFormat)] = 1;
        $this->reservations[$end->format(self::dateFormat)]   = 1;

        $day     = clone $begin;
        $lastDay = clone $end->subDay();

        do {
            $day->addDay();
            $this->reservations[$day->format(self::dateFormat)] = 2;
        } while ($day->lt($lastDay));
    }
}