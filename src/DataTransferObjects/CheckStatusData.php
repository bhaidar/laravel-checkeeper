<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

use Bhaidar\Checkeeper\Enums\CheckStatus;

readonly class CheckStatusData
{
    public function __construct(
        public string $id,
        public CheckStatus $status,
        public ?string $requestId = null,
        public ?bool $test = null,
        public ?string $created = null,
        public ?string $updated = null,
        public ?string $printed = null,
        public ?string $mailed = null,
        public ?string $deliveryMethod = null,
        public ?string $trackingNumber = null,
        public ?string $trackingUrl = null,
        public ?array $meta = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'status' => $this->status->value,
            'request_id' => $this->requestId,
            'test' => $this->test,
            'created' => $this->created,
            'updated' => $this->updated,
            'printed' => $this->printed,
            'mailed' => $this->mailed,
            'delivery_method' => $this->deliveryMethod,
            'tracking_number' => $this->trackingNumber,
            'tracking_url' => $this->trackingUrl,
            'meta' => $this->meta,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: CheckStatus::from($data['status']),
            requestId: $data['request_id'] ?? null,
            test: $data['test'] ?? null,
            created: $data['created'] ?? null,
            updated: $data['updated'] ?? null,
            printed: $data['printed'] ?? null,
            mailed: $data['mailed'] ?? null,
            deliveryMethod: $data['delivery_method'] ?? null,
            trackingNumber: $data['tracking_number'] ?? null,
            trackingUrl: $data['tracking_url'] ?? null,
            meta: $data['meta'] ?? null,
        );
    }
}
