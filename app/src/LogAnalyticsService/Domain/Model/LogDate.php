<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

use DateTimeImmutable;

final readonly class LogDate
{
    private const FORMAT = 'Y-m-d\TH:i:s';

    public function __construct(public DateTimeImmutable $date)
    {
    }

    public function __toString(): string
    {
        return $this->date->format(self::FORMAT);
    }
}
