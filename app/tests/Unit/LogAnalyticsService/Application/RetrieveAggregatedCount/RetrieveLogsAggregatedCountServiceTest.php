<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Application\RetrieveAggregatedCount;

use App\LogAnalyticsService\Application\RetrieveAggregatedCount\Dto\RetrieveServiceLogAggregatedCountRequest;
use App\LogAnalyticsService\Application\RetrieveAggregatedCount\RetrieveLogsAggregatedCountService;
use App\LogAnalyticsService\Domain\Model\AggregatedCount;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class RetrieveLogsAggregatedCountServiceTest extends TestCase
{
    public function test_should_return_aggregated_count_as_expected(): void
    {
        $retrieveAggregatedCountRequest = new RetrieveServiceLogAggregatedCountRequest(
            ['USER-SERVICE'],
            '2024-04-01T00:00:00Z',
            '2024-04-30T23:59:59Z',
            200
        );

        $serviceLogRepositoryMock = $this->createMock(ServiceLogRepositoryInterface::class);
        $serviceLogRepositoryMock->expects(self::once())->method('all')->willReturn(new AggregatedCount(50000));

        $retrieveLogsAggregatedCountService = new RetrieveLogsAggregatedCountService(
            $serviceLogRepositoryMock,
        );

        $actual = $retrieveLogsAggregatedCountService->__invoke($retrieveAggregatedCountRequest);
        self::assertSame(50000, $actual->count);
    }
}
