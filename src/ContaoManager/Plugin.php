<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use ContaoThemesShop\VacancyCalendar\ContaoThemesShopVacancyCalendarBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoThemesShopVacancyCalendarBundle::class)->setLoadAfter(
                [ContaoCoreBundle::class]
            ),
        ];
    }

    /**
     * @param array<mixed> $managerConfig
     */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load('@ContaoThemesShopVacancyCalendarBundle/Resources/config/listeners.yaml');
        $loader->load('@ContaoThemesShopVacancyCalendarBundle/Resources/config/repositories.yaml');
        $loader->load('@ContaoThemesShopVacancyCalendarBundle/Resources/config/modules.yaml');
    }
}
