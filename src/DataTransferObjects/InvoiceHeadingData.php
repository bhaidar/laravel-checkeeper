<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class InvoiceHeadingData
{
    public function __construct(
        public string $label,
        public ?int $size = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'label' => $this->label,
            'size' => $this->size,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            label: $data['label'],
            size: $data['size'] ?? null,
        );
    }
}
