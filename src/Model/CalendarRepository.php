<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\Model;

use Doctrine\DBAL\Connection;
use Netzmacht\Contao\Toolkit\Data\Model\ContaoRepository;

class CalendarRepository extends ContaoRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * {@inheritDoc}
     */
    public function __construct(Connection $connection)
    {
        parent::__construct(CalendarModel::class);

        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
