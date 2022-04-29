<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\Controller\FrontendModule;

use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Model;
use Contao\StringUtil;
use ContaoThemesShop\VacancyCalendar\Generator\CalendarGenerator;
use ContaoThemesShop\VacancyCalendar\Model\CalendarRepository;
use ContaoThemesShop\VacancyCalendar\Model\ReservationRepository;
use Netzmacht\Contao\Toolkit\Controller\FrontendModule\AbstractFrontendModuleController;
use Netzmacht\Contao\Toolkit\Response\ResponseTagger;
use Netzmacht\Contao\Toolkit\Routing\RequestScopeMatcher;
use Netzmacht\Contao\Toolkit\View\Template\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @FrontendModule("vacancy_calendar", category="miscellaneous")
 */
final class VacancyCalendarController extends AbstractFrontendModuleController
{
    private $calendarRepository;

    private $reservationRepository;

    public function __construct(
        TemplateRenderer $templateRenderer,
        RequestScopeMatcher $scopeMatcher,
        ResponseTagger $responseTagger,
        RouterInterface $router,
        TranslatorInterface $translator,
        CalendarRepository $calendarRepository,
        ReservationRepository $reservationRepository
    ) {
        parent::__construct($templateRenderer, $scopeMatcher, $responseTagger, $router, $translator);

        $this->calendarRepository    = $calendarRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function prepareTemplateData(array $data, Request $request, Model $model): array
    {
        $calendar          = $this->calendarRepository->find((int) $model->vc_calendar);
        $reservations      = $this->reservationRepository->findByCalendar((int) $model->vc_calendar);
        $calendarGenerator = new CalendarGenerator($this->translator);
        $calendarGenerator->addReservations($reservations);

        $months = [];

        for ($i = 0; $i < (int) $model->vc_months; ++$i) {
            $months[] = $calendarGenerator->generateMonth(
                $i,
                (bool) $model->vc_short_month,
                (bool) $model->vc_short_day
            );
        }

        $data['title']  = $calendar->title;
        $data['months'] = $months;
        $data['class']  = sprintf('vacancy-calendar-%s', $model->id);
        $data['styles'] = $this->prepareStyles($model);

        return $data;
    }

    private function prepareStyles(Model $model): ?array
    {
        if (false === (bool) $model->vc_add_style) {
            return null;
        }

        $colorFields = ['vc_color_empty', 'vc_color_vacant', 'vc_color_full', 'vc_color_option'];
        $styles      = [];

        foreach ($colorFields as $colorField) {
            $styles[$colorField] = StringUtil::deserialize($model->{$colorField});
        }

        return $styles;
    }
}
