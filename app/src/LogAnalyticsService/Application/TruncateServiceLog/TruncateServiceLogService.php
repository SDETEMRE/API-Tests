<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Application\TruncateServiceLog;

use App\LogAnalyticsService\Domain\Service\Repository\ServiceLogRepositoryInterface;

final readonly class TruncateServiceLogService
{
    public function __construct(
        private ServiceLogRepositoryInterface $serviceLogsRepository,
    ) {
    }

    public function __invoke(): void
    {
        $this->serviceLogsRepository->removeAll();
    }
}
