<?php

declare(strict_types=1);

namespace App\Tests\Unit\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\InvalidProtocolException;
use App\LogAnalyticsService\Domain\Model\Protocol;
use PHPUnit\Framework\TestCase;

final class ProtocolTest extends TestCase
{
    /** @dataProvider provide_valid_protocols */
    public function test_protocol_works_as_expected(string $validProtocol): void
    {
        $protocol = new Protocol($validProtocol);

        self::assertSame(mb_strtolower($validProtocol), (string) $protocol);
    }

    public static function provide_valid_protocols(): array
    {
        return [
            ['HTTP'],
            ['HTTP/1.1'],
            ['HTTPS'],
            ['TLS'],
            ['SSL'],
            ['FTP'],
            ['FTPS'],
        ];
    }

    public function test_should_throw_exception_if_protocol_is_empty(): void
    {
        $this->expectException(InvalidProtocolException::class);

        new Protocol('');
    }

    /** @dataProvider provide_invalid_protocols */
    public function test_should_throw_exception_if_protocol_is_invalid(string $invalidProtocol): void
    {
        $this->expectException(InvalidProtocolException::class);

        new Protocol($invalidProtocol);
    }

    public static function provide_invalid_protocols(): array
    {
        return [
            ['AAA'],
            ['HTTPD'],
        ];
    }
}
