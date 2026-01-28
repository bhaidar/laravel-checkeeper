<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

use Bhaidar\Checkeeper\Enums\DeliveryMethod;

readonly class DeliveryData
{
    public function __construct(
        public DeliveryMethod $method,
        public ?AddressData $bundleAddress = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'ship_method' => $this->method->value,
        ];

        if ($this->bundleAddress !== null) {
            $data['bundle_address'] = $this->bundleAddress->toArray();
        }

        return $data;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            method: DeliveryMethod::from($data['ship_method']),
            bundleAddress: isset($data['bundle_address'])
                ? AddressData::fromArray($data['bundle_address'])
                : null,
        );
    }
}
