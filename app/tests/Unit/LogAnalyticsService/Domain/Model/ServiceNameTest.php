<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\EmptyServiceNameException;
use App\LogAnalyticsService\Domain\Model\ServiceName;
use PHPUnit\Framework\TestCase;

final class ServiceNameTest extends TestCase
{
    public function test_service_name_works_as_expected(): void
    {
        $serviceName = new ServiceName('dummy-service-name');

        self::assertSame('dummy-service-name', (string) $serviceName);
    }

    public function test_should_throw_exception_if_status_code_is_empty(): void
    {
        $this->expectException(EmptyServiceNameException::class);

        new ServiceName('');
    }
}
