<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\Http\Response\Error\Factory;

use App\LogAnalyticsService\UserInterface\Http\Response\Error\FormatTypeTrait;
use App\LogAnalyticsService\UserInterface\Http\Response\Error\JsonErrorResponseDto;

final readonly class ErrorFactory
{
    use FormatTypeTrait;

    public const ERROR_BAD_REQUEST = 'bad-request';
    public const ERROR_UNDEFINED = 'undefined';

    private const NAMESPACE = 'log-analytics-service';
    private const TYPE = 'errors';

    private const ACTION_OPEN_API = 'open-api';
    private const ACTION_RETRIEVE_AGGREGATED_LOG = 'retrieve_aggregated_log';
    private const ACTION_TRUNCATE_SERVICE_LOGS = 'truncate_service_log';

    /** @param string[] $detail */
    public static function ofRetrieveAggregatedCount(string $message, string $errorCode, array $detail = []): JsonErrorResponseDto
    {
        $title = self::formatType(
            self::NAMESPACE,
            self::TYPE,
            self::ACTION_RETRIEVE_AGGREGATED_LOG,
            $errorCode
        );

        return new JsonErrorResponseDto($title, $message, $detail);
    }

    public static function ofTruncateServiceLogs(string $message, string $errorCode, array $detail = []): JsonErrorResponseDto
    {
        $title = self::formatType(
            self::NAMESPACE,
            self::TYPE,
            self::ACTION_TRUNCATE_SERVICE_LOGS,
            $errorCode
        );

        return new JsonErrorResponseDto($title, $message, $detail);
    }

    public static function ofOpenApi(string $message, string $errorCode, array $detail = []): JsonErrorResponseDto
    {
        $title = self::formatType(
            self::NAMESPACE,
            self::TYPE,
            self::ACTION_OPEN_API,
            $errorCode
        );

        return new JsonErrorResponseDto($title, $message, $detail);
    }
}
