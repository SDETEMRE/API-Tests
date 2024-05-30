<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Application\RetrieveAggregatedCount;

use App\LogAnalyticsService\Application\RetrieveAggregatedCount\Dto\RetrieveServiceLogAggregatedCountRequest;
use App\LogAnalyticsService\Application\RetrieveAggregatedCount\Dto\RetrieveServiceLogAggregatedCountResponse;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\ServiceNames;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use DateTimeImmutable;

final readonly class RetrieveLogsAggregatedCountService
{
    public function __construct(
        private ServiceLogRepositoryInterface $serviceLogsRepository,
    ) {
    }

    public function __invoke(RetrieveServiceLogAggregatedCountRequest $retrieveAggregatedLogRequest): RetrieveServiceLogAggregatedCountResponse
    {
        $aggregatedCount = $this->serviceLogsRepository->all(
            serviceNames: new ServiceNames(array_map(static fn ($serviceName) => new ServiceName($serviceName), $retrieveAggregatedLogRequest->serviceNames)),
            statusCode: null !== $retrieveAggregatedLogRequest->statusCode ? new StatusCode($retrieveAggregatedLogRequest->statusCode) : null,
            startDate: null !== $retrieveAggregatedLogRequest->startDate ? new LogDate(new DateTimeImmutable($retrieveAggregatedLogRequest->startDate)) : null,
            endDate: null !== $retrieveAggregatedLogRequest->endDate ? new LogDate(new DateTimeImmutable($retrieveAggregatedLogRequest->endDate)) : null,
        );

        return new RetrieveServiceLogAggregatedCountResponse($aggregatedCount->count);
    }
}
