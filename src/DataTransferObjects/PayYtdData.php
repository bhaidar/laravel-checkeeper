<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class PayYtdData
{
    public function __construct(
        public ?string $gross = null,
        public ?string $net = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'gross' => $this->gross,
            'net' => $this->net,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            gross: $data['gross'] ?? null,
            net: $data['net'] ?? null,
        );
    }
}
