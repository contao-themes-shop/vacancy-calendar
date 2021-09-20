<?php

declare(strict_types=1);

/*
 * This file is part of contao-themes-shop/vacancy-calendar.
 *
 * (c) Christopher Boelter - Contao Themes Shop
 *
 */

namespace ContaoThemesShop\VacancyCalendar\Model;

use Contao\Model\Collection;
use Doctrine\DBAL\Connection;
use Netzmacht\Contao\Toolkit\Data\Model\ContaoRepository;

class ReservationRepository extends ContaoRepository
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
        parent::__construct(ReservationModel::class);

        $this->connection = $connection;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function findExistingDate(int $begin, int $end, int $currentId): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select(['tcvcr.id'])
            ->from(ReservationModel::getTable(), 'tcvcr')
            ->where($queryBuilder->expr()->or(
                ':begin BETWEEN tcvcr.begin AND tcvcr.end',
                ':end BETWEEN tcvcr.begin AND tcvcr.end'
            ))
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->andWhere($queryBuilder->expr()->neq('tcvcr.id', ':currentId'))
            ->setParameter('currentId', $currentId)
        ;

        return $queryBuilder->execute()->rowCount();
    }

    public function findByCalendar(int $calendarId): ?Collection
    {
        return $this->findBy(['.pid=?'], [$calendarId], ['order' => 'begin ASC']);
    }
}
