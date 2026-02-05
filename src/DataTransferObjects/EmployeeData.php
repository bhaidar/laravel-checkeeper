<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class EmployeeData
{
    public function __construct(
        public ?string $social = null,
        public ?string $status = null,
        public ?WithholdingsData $withholdings = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'social' => $this->social,
            'status' => $this->status,
            'withholdings' => $this->withholdings?->toArray(),
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            social: $data['social'] ?? null,
            status: $data['status'] ?? null,
            withholdings: isset($data['withholdings'])
                ? WithholdingsData::fromArray($data['withholdings'])
                : null,
        );
    }
}
