<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\EmptyServiceNameException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class ServiceName
{
    public function __construct(
        private string $name
    ) {
        try {
            Assert::notWhitespaceOnly($this->name);
        } catch (InvalidArgumentException) {
            throw new EmptyServiceNameException(['Provided Service Name Cannot be empty']);
        }
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
