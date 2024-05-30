<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Infrastructure\Repository;

use App\LogAnalyticsService\Domain\Model\AggregatedCount;
use App\LogAnalyticsService\Domain\Model\HttpMethod;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\Protocol;
use App\LogAnalyticsService\Domain\Model\Route;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\ServiceNames;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Exception;

use function count;

final readonly class DoctrineDbalServiceLogRepository implements ServiceLogRepositoryInterface
{
    public function __construct(
        private Connection $defaultConnection,
    ) {
    }

    public function all(
        ServiceNames $serviceNames,
        ?StatusCode $statusCode,
        ?LogDate $startDate,
        ?LogDate $endDate
    ): AggregatedCount {
        $query = $this->defaultConnection->createQueryBuilder();
        $query->select('count(*) as count')
            ->from('service_log');

        if (0 < count($serviceNames->toArray())) {
            $names = array_map(static fn ($serviceName) => sprintf("'%s'", $serviceName), $serviceNames->toArray());
            $query->where($query->expr()->in('service_name', $names));
        }

        if (null !== $statusCode) {
            $query->andWhere($query->expr()->eq('http_code', ':http_code'))
                ->setParameter('http_code', $statusCode->status);
        }

        if (null !== $startDate) {
            $query->andWhere($query->expr()->gte('date_time', ':date_time_start'))
                ->setParameter('date_time_start', $startDate->date->format(DateTimeInterface::ATOM));
        }

        if (null !== $endDate) {
            $query->andWhere($query->expr()->lte('date_time', ':date_time_end'))
                ->setParameter('date_time_end', $endDate->date->format(DateTimeInterface::ATOM));
        }

        $result = $query->executeQuery();

        return new AggregatedCount($result->fetchAssociative()['count']);
    }

    public function removeAll(): void
    {
        $query = $this->defaultConnection->createQueryBuilder();
        $query->delete('service_log')->executeQuery();
    }

    public function store(
        ServiceName $serviceName,
        LogDate $startDate,
        HttpMethod $httpMethod,
        Route $route,
        Protocol $protocol,
        StatusCode $statusCode
    ): void {
        $query = $this->defaultConnection->createQueryBuilder();

        try {
            $query
                ->insert('service_log')
                ->values([
                    'service_name' => ':service_name',
                    'date_time'    => ':date_time',
                    'http_method'  => ':http_method',
                    'route'        => ':route',
                    'protocol'     => ':protocol',
                    'http_code'    => ':http_code',
                ])
                ->setParameters(
                    [
                        'service_name' => (string) $serviceName,
                        'date_time'    => $startDate->date->format(DateTimeInterface::ATOM),
                        'http_method'  => $httpMethod->value,
                        'route'        => (string) $route,
                        'protocol'     => (string) $protocol,
                        'http_code'    => $statusCode->status,
                        'created_at'   => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
                    ]
                )
                ->executeStatement();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
