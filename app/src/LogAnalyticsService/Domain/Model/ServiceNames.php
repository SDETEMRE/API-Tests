<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

final readonly class ServiceNames
{
    public function __construct(
        /** @var ServiceName[] $serviceNames * */
        private array $serviceNames
    ) {
    }

    public function toArray(): array
    {
        return $this->serviceNames;
    }
}
