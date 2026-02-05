<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class WithholdingsData
{
    public function __construct(
        public ?string $federal = null,
        public ?string $state = null,
        public ?string $local = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'federal' => $this->federal,
            'state' => $this->state,
            'local' => $this->local,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            federal: $data['federal'] ?? null,
            state: $data['state'] ?? null,
            local: $data['local'] ?? null,
        );
    }
}
