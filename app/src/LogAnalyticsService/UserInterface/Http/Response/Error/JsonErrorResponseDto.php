<?php

declare(strict_types=1);

namespace App\LogAnalyticsService\UserInterface\Http\Response\Error;

use JsonSerializable;

final readonly class JsonErrorResponseDto implements JsonSerializable
{
    /**
     * @psalm-param non-empty-string $type
     * @psalm-param string[] $detail
     */
    public function __construct(
        public string $type,
        public string $title,
        public array $detail = [],
    ) {
    }

    /** @return array{type: non-empty-string, title: string, detail?:string[]} */
    public function jsonSerialize(): array
    {
        $response = [
            'type'  => $this->type,
            'title' => $this->title,
        ];

        if (!empty($this->detail)) {
            $response['detail'] = $this->detail;
        }

        return $response;
    }
}
