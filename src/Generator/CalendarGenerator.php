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
        for ($i = 1; $i < ($month->dayOfWeek === 0 ? 7 : $month->dayOfWeek); $i++) {
            $data['weeks'][$week][] = ['class' => 'empty'];
        }

        $day = clone $month;
        do {
            $dayBefore = clone $day->subDay();
            $dayAfter  = clone $day->addDay();
            $classes   = [];

            if (is_array($this->reservations[$day->format(self::dateFormat)]) === false) {
                $classes[] = 'vacant';
            } else {
                if ($this->reservations[$day->format(self::dateFormat)]['state'] == 1) {
                    if ($this->reservations[$dayBefore->format(self::dateFormat)]['state'] <= 1) {
                        $classes[] = 'begin';
                        $classes = array_merge($classes, $this->reservations[$day->format(self::dateFormat)]['additionalClasses']);

                    } elseif ($this->reservations[$dayAfter->format(self::dateFormat)]['state'] < 2) {
                        $classes[] = 'end';
                        $classes = array_merge($classes, $this->reservations[$day->format(self::dateFormat)]['additionalClasses']);

                    } else {
                        $classes[] = 'full';
                        $classes = array_merge($classes, $this->reservations[$day->format(self::dateFormat)]['additionalClasses']);
                    }

                } elseif ($this->reservations[$day->format(self::dateFormat)]['state'] > 1) {
                    $classes[] = 'full';
                }
            }

            $data['weeks'][$week][] = ['class' => implode(' ', $classes), 'day' => $day->day];

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

        if ($begin->eq($end)) {
            $this->addToReservations($begin->format(self::dateFormat), 2, $reservationModel);
            return;
        }

        $this->addToReservations(
            $begin->format(self::dateFormat),
            (array_key_exists($begin->format(self::dateFormat), $this->reservations) ? 2 : 1),
            $reservationModel
        );
        $this->addToReservations(
            $end->format(self::dateFormat),
            1,
            $reservationModel
        );

        $day     = clone $begin;
        $lastDay = clone $end->subDay();

        do {
            $day->addDay();
            $this->addToReservations(
                $day->format(self::dateFormat),
                2,
                $reservationModel
            );
        } while ($day->lt($lastDay));
    }

    private function addToReservations(string $date, int $state, ReservationModel $reservationModel): void
    {
        $this->reservations[$date] = [
            'state'             => $state,
            'additionalClasses' => $this->addAdditionalClasses($reservationModel),
        ];
    }

    private function addAdditionalClasses(ReservationModel $reservationModel): array
    {
        $additionalClasses = [];

        if ((bool) $reservationModel->isOption === true) {
            $additionalClasses[] = 'is-option';
        }

        return $additionalClasses;
    }
}
