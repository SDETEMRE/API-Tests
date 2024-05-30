<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\InvalidStatusCodeException;
use App\LogAnalyticsService\Domain\Model\StatusCode;
use PHPUnit\Framework\TestCase;

final class StatusCodeTest extends TestCase
{
    public function test_status_code_works_as_expected(): void
    {
        $email = new StatusCode(200);

        self::assertSame(200, $email->status);
    }

    /** @dataProvider provide_invalid_status_codes */
    public function test_should_throw_exception_if_status_code_is_not_valid(int $statusCodes): void
    {
        $this->expectException(InvalidStatusCodeException::class);

        new StatusCode($statusCodes);
    }

    public static function provide_invalid_status_codes(): array
    {
        return [
            [99],
            [600],
        ];
    }
}
