<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class AddressData
{
    public function __construct(
        public string $name,
        public string $line1,
        public string $city,
        public string $state,
        public string $zip,
        public ?string $company = null,
        public ?string $line2 = null,
        public ?string $line3 = null,
        public ?string $country = null,
        public ?string $phone = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'company' => $this->company,
            'line1' => $this->line1,
            'line2' => $this->line2,
            'line3' => $this->line3,
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
            city: $data['city'],
            state: $data['state'],
            zip: $data['zip'],
            company: $data['company'] ?? null,
            line2: $data['line2'] ?? null,
            line3: $data['line3'] ?? null,
            country: $data['country'] ?? null,
            phone: $data['phone'] ?? null,
        );
    }
}
