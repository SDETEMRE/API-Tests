<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Application\StoreServiceLog;

use App\LogAnalyticsService\Application\StoreServiceLog\Dto\StoreLogServiceRequest;
use App\LogAnalyticsService\Application\StoreServiceLog\StoreLogService;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogImporterInterface;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use PHPUnit\Framework\TestCase;

final class StoreLogServiceTest extends TestCase
{
    public function test_should_store_log_as_expected(): void
    {
        $serviceLogImporter = $this->createMock(ServiceLogImporterInterface::class);
        $serviceLogImporter->expects(self::once())->method('import');

        $storeLogService = new StoreLogService(
            $serviceLogImporter,
        );

        $storeLogService->__invoke(
            new StoreLogServiceRequest(
                filePath: 'dummy-file-path',
            )
        );
    }
}
