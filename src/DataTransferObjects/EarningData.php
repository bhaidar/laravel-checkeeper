<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class EarningData
{
    public function __construct(
        public ?string $type = null,
        public ?string $rate = null,
        public ?string $hours = null,
        public ?string $period = null,
        public ?string $ytd = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'rate' => $this->rate,
            'hours' => $this->hours,
            'period' => $this->period,
            'ytd' => $this->ytd,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: $data['type'] ?? null,
            rate: $data['rate'] ?? null,
            hours: $data['hours'] ?? null,
            period: $data['period'] ?? null,
            ytd: $data['ytd'] ?? null,
        );
    }
}
