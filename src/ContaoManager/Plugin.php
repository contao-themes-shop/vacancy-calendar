<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use ContaoThemesShop\VacancyCalendar\ContaoThemesShopVacancyCalendarBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

/** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
class Plugin implements BundlePluginInterface, ConfigPluginInterface
{
    /**
     * {@inheritDoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoThemesShopVacancyCalendarBundle::class)->setLoadAfter(
                [ContaoCoreBundle::class],
            ),
        ];
    }

    /** @param array<mixed> $managerConfig */
    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(__DIR__ . '/../../config/listeners.yaml');
        $loader->load(__DIR__ . '/../../config/repositories.yaml');
        $loader->load(__DIR__ . '/../../config/modules.yaml');
    }
}
