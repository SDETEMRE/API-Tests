<?php

declare(strict_types=1);

namespace App\Tests\Integration\LogAnalyticsService\Infrastructure\Extractor;

use App\LogAnalyticsService\Infrastructure\Extractor\LogParser;
use PHPUnit\Framework\TestCase;

final class LogParserTest extends TestCase
{
    public function test_parse_log_line_should_work_as_expected(): void
    {
        $logParser = new LogParser();

        $line = 'USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] "POST /users HTTP/1.1" 201\r\n';
        $parsedLine = $logParser->parse($line);

        self::assertSame('USER-SERVICE', $parsedLine->serviceName);
        self::assertSame('17/Aug/2018:09:21:53 +0000', $parsedLine->logDate);
        self::assertSame('POST', $parsedLine->httpMethod);
        self::assertSame('/users', $parsedLine->route);
        self::assertSame('HTTP/1.1', $parsedLine->protocol);
        self::assertSame(201, $parsedLine->statusCode);
    }
}
