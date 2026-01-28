<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class PayeeData
{
    public function __construct(
        public string $line1,
        public ?string $line2 = null,
        public ?string $line3 = null,
        public ?string $line4 = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'line1' => $this->line1,
            'line2' => $this->line2,
            'line3' => $this->line3,
            'line4' => $this->line4,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            line1: $data['line1'],
            line2: $data['line2'] ?? null,
            line3: $data['line3'] ?? null,
            line4: $data['line4'] ?? null,
        );
    }
}
