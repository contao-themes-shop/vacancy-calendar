<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use function dirname;

class ContaoThemesShopVacancyCalendarBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }

    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
