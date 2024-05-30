<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\EmptyRouteException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final readonly class Route
{
    public function __construct(
        private string $route
    ) {
        try {
            Assert::notWhitespaceOnly($this->route);
        } catch (InvalidArgumentException) {
            throw new EmptyRouteException(['Provided Route Cannot be empty']);
        }
    }

    public function __toString(): string
    {
        return $this->route;
    }
}
