<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class BankData
{
    public function __construct(
        public string $routing,
        public string $account,
    ) {
    }

    public function toArray(): array
    {
        return [
            'routing' => $this->routing,
            'account' => $this->account,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            routing: $data['routing'],
            account: $data['account'],
        );
    }
}
