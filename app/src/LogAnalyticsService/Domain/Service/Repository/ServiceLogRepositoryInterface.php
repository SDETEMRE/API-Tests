<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Service\Repository;

use App\LogAnalyticsService\Domain\Model\AggregatedCount;
use App\LogAnalyticsService\Domain\Model\HttpMethod;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\Protocol;
use App\LogAnalyticsService\Domain\Model\Route;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\ServiceNames;
use App\LogAnalyticsService\Domain\Model\StatusCode;

interface ServiceLogRepositoryInterface
{
    public function all(ServiceNames $serviceNames, ?StatusCode $statusCode, ?LogDate $startDate, ?LogDate $endDate): AggregatedCount;

    public function removeAll(): void;

    public function store(ServiceName $serviceName, LogDate $startDate, HttpMethod $httpMethod, Route $route, Protocol $protocol, StatusCode $statusCode): void;
}
