<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Infrastructure\Extractor;

final readonly class ParsedLine
{
    public function __construct(
        public string $serviceName,
        public string $logDate,
        public string $httpMethod,
        public string $route,
        public string $protocol,
        public int $statusCode,
    ) {
    }
}
