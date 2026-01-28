<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

use Bhaidar\Checkeeper\Enums\SignerType;

readonly class SignerData
{
    public function __construct(
        public SignerType $type,
        public string $value,
        public ?string $value2 = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'type' => $this->type->value,
            'value' => $this->value,
        ];

        if ($this->value2 !== null) {
            $data['value2'] = $this->value2;
        }

        return $data;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            type: SignerType::from($data['type']),
            value: $data['value'],
            value2: $data['value2'] ?? null,
        );
    }
}
