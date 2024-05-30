<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\Domain\Model;

use App\LogAnalyticsService\Domain\Exception\InvalidProtocolException;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

final class Protocol
{
    private const PROTOCOLS = ['http', 'https', 'tls', 'ssl', 'ftp', 'ftps'];

    public function __construct(
        private readonly string $protocol
    ) {
        $this->validate();
    }

    public function __toString(): string
    {
        return mb_strtolower($this->protocol);
    }

    public function equals(?self $other): bool
    {
        return (string) $this === (string) $other;
    }

    private function validate(): void
    {
        try {
            $protocolName = $this->protocol;
            if (str_contains($this->protocol, '/')) {
                $protocolName = explode('/', $this->protocol)[0];
            }
            Assert::inArray(mb_strtolower($protocolName), self::PROTOCOLS);
        } catch (InvalidArgumentException) {
            throw new InvalidProtocolException($this->protocol);
        }
    }
}
