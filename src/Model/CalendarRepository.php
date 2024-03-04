<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Model;

use Doctrine\DBAL\Connection;
use Netzmacht\Contao\Toolkit\Data\Model\ContaoRepository;

class CalendarRepository extends ContaoRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct(CalendarModel::class);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
