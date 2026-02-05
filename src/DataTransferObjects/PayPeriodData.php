<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class PayPeriodData
{
    public function __construct(
        public ?string $gross = null,
        public ?string $net = null,
        public ?string $starting = null,
        public ?string $ending = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'gross' => $this->gross,
            'net' => $this->net,
            'starting' => $this->starting,
            'ending' => $this->ending,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            gross: $data['gross'] ?? null,
            net: $data['net'] ?? null,
            starting: $data['starting'] ?? null,
            ending: $data['ending'] ?? null,
        );
    }
}
