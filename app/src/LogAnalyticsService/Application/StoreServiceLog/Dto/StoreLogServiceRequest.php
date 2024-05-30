<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Application\StoreServiceLog\Dto;

final readonly class StoreLogServiceRequest
{
    public function __construct(
        public string $filePath,
    ) {
    }
}
