<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\EventListener\DataContainer;

use Codefog\HasteBundle\Formatter;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Framework\Adapter;
use Contao\DataContainer;
use ContaoThemesShop\VacancyCalendar\Model\ReservationRepository;
use Netzmacht\Contao\Toolkit\View\Template\TemplateRenderer;
use Symfony\Contracts\Translation\TranslatorInterface;

use function array_key_exists;

final class VacancyCalendarReservationDcaListener
{
    public function __construct(
        private readonly TemplateRenderer $templateRenderer,
        private readonly Adapter $message,
        private readonly Adapter $system,
        private readonly Adapter $controller,
        private readonly TranslatorInterface $translator,
        private readonly ReservationRepository $reservations,
        private readonly Formatter $formatter,
    ) {
    }

    /** @SuppressWarnings(PHPMD.Superglobals) */
    #[AsCallback(table: 'tl_cts_vacancy_calendar_reservation', target: 'config.onsubmit')]
    public function onSubmit(DataContainer $dataContainer): void
    {
        $existingDates = $this->reservations->findExistingDate(
            (int) $dataContainer->activeRecord->begin,
            (int) $dataContainer->activeRecord->end,
            (int) $dataContainer->activeRecord->id,
            (int) $dataContainer->activeRecord->pid,
        );

        if ($existingDates === 0) {
            return;
        }

        $this->message->addError(
            $this->translator->trans(
                'tl_cts_vacancy_calendar_reservation.messages.error_date',
                [$dataContainer->activeRecord->title],
                'contao_tl_cts_vacancy_calendar_reservation',
            ),
        );

        if (array_key_exists('save', $_POST) === true) {
            return;
        }

        $this->controller->redirect($this->system->getReferer(false, 'tl_cts_vacancy_calendar_reservation'));
    }

    /** @param mixed[] $row */
    #[AsCallback(table: 'tl_cts_vacancy_calendar_reservation', target: 'list.sorting.child_record')]
    public function onChildRecord(array $row): string
    {
        $row['begin'] = $this->formatter->date((int) $row['begin']);
        $row['end']   = $this->formatter->date((int) $row['end']);

        return $this->templateRenderer->render('toolkit:be:be_cts_vacancy_calendar_reservation.html5', $row);
    }

    #[AsCallback(table: 'tl_cts_vacancy_calendar_reservation', target: 'fields.begin.load')]
    #[AsCallback(table: 'tl_cts_vacancy_calendar_reservation', target: 'fields.end.load')]
    public function onDateLoad(string|null $value): string
    {
        if ($value === null || (int) $value === 0) {
            return '';
        }

        return $value;
    }
}
