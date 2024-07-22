<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Generator;

use Carbon\Carbon;
use Contao\Model\Collection;
use ContaoThemesShop\VacancyCalendar\Model\ReservationModel;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_key_exists;
use function count;
use function is_array;
use function ksort;
use function sprintf;

/**
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
final class CalendarGenerator
{
    private const DATE_FORMAT = 'Ymd';

    /** @var mixed[][] */
    private array $reservations = [];

    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /** @return mixed[] */
    public function generateMonth(int $month, bool $monthShort, bool $dayShort): array
    {
        $currentMonth  = (new Carbon())->month;
        $month         = Carbon::createFromDate(null, $currentMonth + $month, 1);
        $monthLabelKey = $monthShort ? 'MONTHS_SHORT' : 'MONTHS';
        $dayLabelKey   = $dayShort ? 'DAYS_SHORT' : 'DAYS';

        $data                   = [];
        $data['label']['month'] = $this->translator->trans(
            sprintf('%s.%s', $monthLabelKey, $month->month - 1),
            [],
            'contao_default',
        );
        $data['label']['year']  = $month->year;

        for ($i = 1; $i <= 7; ++$i) {
            if ($i === 7) {
                $data['days'][] = $this->translator->trans(
                    sprintf('%s.%s', $dayLabelKey, 0),
                    [],
                    'contao_default',
                );
                continue;
            }

            $data['days'][] = $this->translator->trans(
                sprintf('%s.%s', $dayLabelKey, $i),
                [],
                'contao_default',
            );
        }

        $week = 1;

        for ($i = 1; $i < ($month->dayOfWeek === 0 ? 7 : $month->dayOfWeek); ++$i) {
            $data['weeks'][$week][] = ['class' => 'empty'];
        }

        $day = clone $month;

        do {
            $dayBefore = clone $day->subDay();
            $dayAfter  = clone $day->addDay();
            $class     = '';

            if (isset($this->reservations[$day->format(self::DATE_FORMAT)]) === false
                || is_array($this->reservations[$day->format(self::DATE_FORMAT)]) === false) {
                $class = 'vacant';
            } else {
                if (isset($this->reservations[$day->format(self::DATE_FORMAT)])
                    && $this->reservations[$day->format(self::DATE_FORMAT)]['state'] === 1) {
                    if ($this->reservations[$dayBefore->format(self::DATE_FORMAT)]['state'] <= 1) {
                        $class = 'begin';
                    } elseif ($this->reservations[$dayAfter->format(self::DATE_FORMAT)]['state'] < 2) {
                        $class = 'end';
                    } else {
                        $class = 'full';
                    }
                } elseif (isset($this->reservations[$day->format(self::DATE_FORMAT)])
                    && $this->reservations[$day->format(self::DATE_FORMAT)]['state'] > 1) {
                    $class = 'full';

                    if (
                        $this->reservations[$day->format(self::DATE_FORMAT)]['state'] > 1
                        && ($this->reservations[$day->format(self::DATE_FORMAT)]['isOption'] === true
                            && $this->reservations[$dayBefore->format(self::DATE_FORMAT)]['isOption'] === false)
                    ) {
                        $class = 'regular-option';
                    }

                    if (
                        $this->reservations[$day->format(self::DATE_FORMAT)]['state'] > 1
                        && ($this->reservations[$day->format(self::DATE_FORMAT)]['isOption'] === false
                            && $this->reservations[$dayBefore->format(self::DATE_FORMAT)]['isOption'] === true)
                    ) {
                        $class = 'option-regular';
                    }
                }

                if (isset($this->reservations[$day->format(self::DATE_FORMAT)])
                    && $this->reservations[$day->format(self::DATE_FORMAT)]['isOption'] === true) {
                    $class .= ' is-option';
                }
            }

            $data['weeks'][$week][] = ['class' => $class, 'day' => $day->day];

            if (count($data['weeks'][$week]) === 7) {
                ++$week;
                $data['weeks'][$week] = [];
            }

            $day->addDay();
        } while ($day->lte($month->endOfMonth()));

        $remainingDays = count($data['weeks'][$week]);

        if ($remainingDays < 7 && $remainingDays > 0) {
            for ($i = 1; $i <= 7 - $remainingDays; ++$i) {
                $data['weeks'][$week][] = ['class' => 'empty'];
            }
        }

        return $data;
    }

    public function addReservations(Collection|null $reservations): void
    {
        foreach ($reservations ?? [] as $reservation) {
            $this->addReservation($reservation);
        }

        ksort($this->reservations);
    }

    public function addReservation(ReservationModel $reservationModel): void
    {
        $begin = Carbon::createFromTimestamp((int) $reservationModel->begin);
        $end   = Carbon::createFromTimestamp((int) $reservationModel->end);

        if ($begin->eq($end)) {
            $this->addToReservations($begin->format(self::DATE_FORMAT), 2, $reservationModel);

            return;
        }

        $this->addToReservations(
            $begin->format(self::DATE_FORMAT),
            (array_key_exists($begin->format(self::DATE_FORMAT), $this->reservations) ? 2 : 1),
            $reservationModel,
        );
        $this->addToReservations(
            $end->format(self::DATE_FORMAT),
            1,
            $reservationModel,
        );

        $day     = clone $begin;
        $lastDay = clone $end->subDay();

        do {
            $day->addDay();
            $this->addToReservations(
                $day->format(self::DATE_FORMAT),
                2,
                $reservationModel,
            );
        } while ($day->lt($lastDay));
    }

    private function addToReservations(string $date, int $state, ReservationModel $reservationModel): void
    {
        $this->reservations[$date] = [
            'state' => $state,
            'isOption' => (bool) $reservationModel->isOption,
        ];
    }
}
