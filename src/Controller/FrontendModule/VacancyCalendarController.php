<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Model;
use Contao\ModuleModel;
use Contao\StringUtil;
use ContaoThemesShop\VacancyCalendar\Generator\CalendarGenerator;
use ContaoThemesShop\VacancyCalendar\Model\CalendarRepository;
use ContaoThemesShop\VacancyCalendar\Model\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

use function sprintf;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
#[AsFrontendModule(category: 'miscellaneous', name: 'vacancy_calendar', template: 'mod_vacancy_calendar')]
final class VacancyCalendarController extends AbstractFrontendModuleController
{
    public function __construct(
        private readonly CalendarRepository $calendars,
        private readonly ReservationRepository $reservations,
        private readonly TranslatorInterface $translator,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $calendar          = $this->calendars->find((int) $model->vc_calendar);
        $reservations      = $this->reservations->findByCalendar((int) $model->vc_calendar);
        $calendarGenerator = new CalendarGenerator($this->translator);
        $calendarGenerator->addReservations($reservations);

        $months = [];

        for ($i = 0; $i < (int) $model->vc_months; ++$i) {
            $months[] = $calendarGenerator->generateMonth(
                $i,
                (bool) $model->vc_short_month,
                (bool) $model->vc_short_day,
            );
        }

        $template->title  = $calendar->title;
        $template->months = $months;
        $template->classCalendar  = sprintf('vacancy-calendar-%s', $model->id);
        $template->styles = $this->prepareStyles($model);

        return $template->getResponse();
    }

    private function prepareStyles(Model $model): array|null
    {
        if ((bool) $model->vc_add_style === false) {
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
