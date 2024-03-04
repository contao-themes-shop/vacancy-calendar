<?php

declare(strict_types=1);

namespace ContaoThemesShop\VacancyCalendar\Model;

use Contao\Model\Collection;
use Doctrine\DBAL\Connection;
use Netzmacht\Contao\Toolkit\Data\Model\ContaoRepository;

class ReservationRepository extends ContaoRepository
{
    /**
     * {@inheritDoc}
     */
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct(ReservationModel::class);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function findExistingDate(int $begin, int $end, int $currentId, int $calendarId): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select(['tcvcr.id'])
            ->from(ReservationModel::getTable(), 'tcvcr')
            ->where($queryBuilder->expr()->or(
                ':begin BETWEEN tcvcr.begin AND tcvcr.end',
                ':end BETWEEN tcvcr.begin AND tcvcr.end',
            ))
            ->setParameter('begin', $begin)
            ->setParameter('end', $end)
            ->andWhere($queryBuilder->expr()->neq('tcvcr.id', ':currentId'))
            ->andWhere($queryBuilder->expr()->eq('tcvcr.pid', $calendarId))
            ->setParameter('currentId', $currentId);

        return $queryBuilder->executeQuery()->rowCount();
    }

    public function findByCalendar(int $calendarId): Collection|null
    {
        return $this->findBy(['.pid=?'], [$calendarId], ['order' => 'begin ASC']);
    }
}
