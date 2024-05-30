<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

final readonly class AggregatedCount
{
    public function __construct(
        public int $count
    ) {
    }
}
