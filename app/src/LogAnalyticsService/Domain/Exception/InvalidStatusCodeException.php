<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Exception;

final class InvalidStatusCodeException extends ValidationException
{
    public function __construct(int $statusCode)
    {
        parent::__construct([sprintf('Provided HTTP status code is: %s. It must be between 100 and 599', $statusCode)]);
    }
}
