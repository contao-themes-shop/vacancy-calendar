<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\EventListener\Dca;

use Contao\Controller;
use Contao\CoreBundle\Framework\Adapter;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\System;
use ContaoThemesShop\VacancyCalendar\Model\ReservationRepository;
use Haste\Util\Format;
use Netzmacht\Contao\Toolkit\View\Template\TemplateRenderer;
use Symfony\Contracts\Translation\TranslatorInterface;

final class VacancyCalendarReservationDcaListener
{
    /**
     * @var TemplateRenderer
     */
    private $templateRenderer;

    /**
     * @var Adapter
     */
    private $input;

    /**
     * @var Adapter
     */
    private $message;

    /**
     * @var System
     */
    private $system;

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var ReservationRepository
     */
    private $reservationRepository;

    public function __construct(TemplateRenderer $templateRenderer, Adapter $input, Adapter $message, Adapter $system, Adapter $controller, TranslatorInterface $translator, ReservationRepository $reservationRepository)
    {
        $this->templateRenderer = $templateRenderer;
        $this->input = $input;
        $this->message = $message;
        $this->system = $system;
        $this->controller = $controller;
        $this->translator = $translator;
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @Callback(table="tl_cts_vacancy_calendar_reservation", target="config.onsubmit")
     */
    public function onSubmit(DataContainer $dataContainer): void
    {
        $existingDates = $this->reservationRepository->findExistingDate(
            (int) $dataContainer->activeRecord->begin,
            (int) $dataContainer->activeRecord->end,
            (int) $dataContainer->activeRecord->id
        );

        if (0 === $existingDates) {
            return;
        }

        $this->message->addError(
            $this->translator->trans(
                'tl_cts_vacancy_calendar_reservation.messages.error_date',
                [$dataContainer->activeRecord->title],
                'contao_tl_cts_vacancy_calendar_reservation'
            )
        );

        if (true === \array_key_exists('save', $_POST)) {
            return;
        }

        $this->controller->redirect($this->system->getReferer(false, 'tl_cts_vacancy_calendar_reservation'));
    }

    /**
     * @Callback(table="tl_cts_vacancy_calendar_reservation", target="list.sorting.child_record")
     */
    public function onChildRecord(array $row): string
    {
        $row['begin'] = Format::date((int) $row['begin']);
        $row['end'] = Format::date((int) $row['end']);

        return $this->templateRenderer->render('toolkit:be:be_cts_vacancy_calendar_reservation.html5', $row);
    }

    /**
     * @Callback(table="tl_cts_vacancy_calendar_reservation", target="fields.begin.load")
     * @Callback(table="tl_cts_vacancy_calendar_reservation", target="fields.end.load")
     */
    public function onDateLoad(?string $value): string
    {
        if (null === $value || 0 === (int) $value) {
            return '';
        }

        return $value;
    }
}
