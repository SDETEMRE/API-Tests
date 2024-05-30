<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Application\RetrieveAggregatedCount\Dto;

final readonly class RetrieveServiceLogAggregatedCountRequest
{
    public function __construct(
        public ?array $serviceNames,
        public ?string $startDate,
        public ?string $endDate,
        public ?int $statusCode,
    ) {
    }
}
