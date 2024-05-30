<?php

declare(strict_types=1);

namespace App\Tests\Mock\Infrastructure\Repository;

use App\LogAnalyticsService\Domain\Model\AggregatedCount;
use App\LogAnalyticsService\Domain\Model\HttpMethod;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\Protocol;
use App\LogAnalyticsService\Domain\Model\Route;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\ServiceNames;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use RuntimeException;

final class ServiceLogRepositorySpy implements ServiceLogRepositoryInterface
{
    public static int $aggregatedCount = 0;
    public static bool $shouldReturnUnHandleableException = false;

    public function all(
        ServiceNames $serviceNames,
        ?StatusCode $statusCode,
        ?LogDate $startDate,
        ?LogDate $endDate
    ): AggregatedCount {
        if (self::$shouldReturnUnHandleableException) {
            throw new RuntimeException();
        }

        return new AggregatedCount(self::$aggregatedCount);
    }

    public function removeAll(): void
    {
        if (self::$shouldReturnUnHandleableException) {
            throw new RuntimeException();
        }
    }

    public function store(ServiceName $serviceName, LogDate $startDate, HttpMethod $httpMethod, Route $route, Protocol $protocol, StatusCode $statusCode): void
    {
    }
}
