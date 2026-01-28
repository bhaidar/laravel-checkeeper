<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class AddressData
{
    public function __construct(
        public string $name,
        public string $line1,
        public ?string $line2 = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zip = null,
        public ?string $country = null,
        public ?string $phone = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'phone' => $this->phone,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            line1: $data['line1'],
            line2: $data['line2'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            zip: $data['zip'] ?? null,
            country: $data['country'] ?? null,
            phone: $data['phone'] ?? null,
        );
    }
}
