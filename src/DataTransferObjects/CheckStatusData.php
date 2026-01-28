<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

use Bhaidar\Checkeeper\Enums\CheckStatus;

readonly class CheckStatusData
{
    public function __construct(
        public string $id,
        public CheckStatus $status,
        public ?string $created = null,
        public ?string $updated = null,
        public ?string $trackingUrl = null,
        public ?array $metadata = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->id,
            'status' => $this->status->value,
            'created' => $this->created,
            'updated' => $this->updated,
            'tracking_url' => $this->trackingUrl,
            'metadata' => $this->metadata,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            status: CheckStatus::from($data['status']),
            created: $data['created'] ?? null,
            updated: $data['updated'] ?? null,
            trackingUrl: $data['tracking_url'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }
}
