<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\InvalidStatusCodeException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class StatusCode
{
    public function __construct(
        public int $status
    ) {
        try {
            Assert::range($status, 100, 599);
        } catch (InvalidArgumentException) {
            throw new InvalidStatusCodeException($this->status);
        }
    }
}
