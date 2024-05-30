<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Exception;

use DomainException;

abstract class ValidationException extends DomainException
{
    /** @param array<string> $violations */
    public function __construct(private readonly array $violations)
    {
        parent::__construct('Validation failed.');
    }

    /** @return array<string> */
    public function violations(): array
    {
        return $this->violations;
    }
}
