<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\EmptyRouteException;
use App\LogAnalyticsService\Domain\Model\Route;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function test_route_works_as_expected(): void
    {
        $route = new Route('/dummy-route');

        self::assertSame('/dummy-route', (string) $route);
    }

    public function test_should_throw_exception_if_route_is_empty(): void
    {
        $this->expectException(EmptyRouteException::class);

        new Route('');
    }
}
