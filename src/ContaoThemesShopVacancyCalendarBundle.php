<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoThemesShopVacancyCalendarBundle extends Bundle
{
    /**
     * @inheritdoc
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
