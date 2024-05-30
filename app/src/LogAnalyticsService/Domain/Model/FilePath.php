<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\EmptyServiceNameException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class FilePath
{
    public function __construct(
        private string $path
    ) {
        try {
            Assert::notWhitespaceOnly($this->path);
        } catch (InvalidArgumentException) {
            throw new EmptyServiceNameException(['Provided File path should not be empty.']);
        }
    }

    public function __toString(): string
    {
        return $this->path;
    }
}
