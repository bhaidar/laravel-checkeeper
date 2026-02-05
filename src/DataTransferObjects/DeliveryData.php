<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

use Bhaidar\Checkeeper\Enums\DeliveryMethod;

readonly class DeliveryData
{
    public function __construct(
        public DeliveryMethod $method,
        public ?AddressData $bundleAddress = null,
        public ?AddressData $bundleReturn = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'method' => $this->method->value,
        ];

        if ($this->bundleAddress !== null) {
            $data['bundle_address'] = $this->bundleAddress->toArray();
        }

        if ($this->bundleReturn !== null) {
            $data['bundle_return'] = $this->bundleReturn->toArray();
        }

        return $data;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            method: DeliveryMethod::from($data['method']),
            bundleAddress: isset($data['bundle_address'])
                ? AddressData::fromArray($data['bundle_address'])
                : null,
            bundleReturn: isset($data['bundle_return'])
                ? AddressData::fromArray($data['bundle_return'])
                : null,
        );
    }
}
