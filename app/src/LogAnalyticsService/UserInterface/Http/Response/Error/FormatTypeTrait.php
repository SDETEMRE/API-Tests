<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\Http\Response\Error;

use function implode;

trait FormatTypeTrait
{
    /** @return non-empty-string */
    private static function formatType(string ...$uriPathSegment): string
    {
        return '/' . implode('/', $uriPathSegment);
    }
}
