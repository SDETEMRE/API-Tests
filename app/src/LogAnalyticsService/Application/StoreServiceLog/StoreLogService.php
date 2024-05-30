<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Application\StoreServiceLog;

use App\LogAnalyticsService\Application\StoreServiceLog\Dto\StoreLogServiceRequest;
use App\LogAnalyticsService\Application\StoreServiceLog\Exception\LogsImportFailedException;
use App\LogAnalyticsService\Domain\Model\FilePath;
use App\LogAnalyticsService\Domain\Model\HttpMethod;
use App\LogAnalyticsService\Domain\Model\LogDate;
use App\LogAnalyticsService\Domain\Model\Protocol;
use App\LogAnalyticsService\Domain\Model\Route;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogImporterInterface;
use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;
use DateTimeImmutable;

final readonly class StoreLogService
{
    public function __construct(
        private ServiceLogImporterInterface $serviceLogsImporter,
    ) {
    }

    public function __invoke(StoreLogServiceRequest $storeLogsRequest): void
    {
        try {
            $this->serviceLogsImporter->import( new FilePath($storeLogsRequest->filePath));
        } catch (\Exception $e) {
            throw new LogsImportFailedException($e->getMessage());
        }
    }
}
