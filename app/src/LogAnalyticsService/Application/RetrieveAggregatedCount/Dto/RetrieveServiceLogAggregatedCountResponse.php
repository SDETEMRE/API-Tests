<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Application\RetrieveAggregatedCount\Dto;

final readonly class RetrieveServiceLogAggregatedCountResponse
{
    public function __construct(
        public int $count,
    ) {
    }
}
