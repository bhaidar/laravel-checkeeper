<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class EventLocationData
{
    public function __construct(
        public ?string $country = null,
        public ?string $zip = null,
        public ?string $state = null,
        public ?string $city = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'country' => $this->country,
            'zip' => $this->zip,
            'state' => $this->state,
            'city' => $this->city,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            country: $data['country'] ?? null,
            zip: $data['zip'] ?? null,
            state: $data['state'] ?? null,
            city: $data['city'] ?? null,
        );
    }
}
