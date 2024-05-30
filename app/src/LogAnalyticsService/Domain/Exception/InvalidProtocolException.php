<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Exception;

final class InvalidProtocolException extends ValidationException
{
    public function __construct(string $protocol)
    {
        parent::__construct([sprintf('%s is not a valid protocol', $protocol)]);
    }
}
