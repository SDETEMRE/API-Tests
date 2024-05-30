<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Application\TruncateServiceLog;

use App\LogAnalyticsService\Application\TruncateServiceLog\TruncateServiceLogService;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class TruncateServiceLogServiceTest extends TestCase
{
    public function test_should_remove_all_stored_logs_as_expected(): void
    {
        $serviceLogRepositoryMock = $this->createMock(ServiceLogRepositoryInterface::class);
        $serviceLogRepositoryMock->expects(self::once())->method('removeAll');

        $truncateServiceLogService = new TruncateServiceLogService(
            $serviceLogRepositoryMock,
        );

        $truncateServiceLogService->__invoke();
    }
}
