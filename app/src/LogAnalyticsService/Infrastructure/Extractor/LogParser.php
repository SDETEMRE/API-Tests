<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Infrastructure\Extractor;

final readonly class LogParser
{
    public function parse(string $line): ParsedLine
    {
        $parts = explode(' ', $line, 2);
        $service = $parts[0];

        preg_match('/\[([^\]]+)\]/', $line, $matches);
        $timestamp = $matches[1];

        $requestParts = explode('"', $parts[1]);
        $requestMethod = explode(' ', $requestParts[1])[0];
        $requestEndpoint = explode(' ', $requestParts[1])[1];
        $requestProtocol = explode(' ', $requestParts[1])[2];
        $responseStatus = str_replace('\r\n', '', trim($requestParts[2]));

        return new ParsedLine(
            $service,
            $timestamp,
            $requestMethod,
            $requestEndpoint,
            $requestProtocol,
            (int) $responseStatus
        );
    }
}
