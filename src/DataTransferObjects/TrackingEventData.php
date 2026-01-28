<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class TrackingEventData
{
    public function __construct(
        public string $event,
        public ?string $subevent = null,
        public ?string $eventDate = null,
        public ?object $location = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'event' => $this->event,
            'subevent' => $this->subevent,
            'event_date' => $this->eventDate,
            'location' => $this->location,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            event: $data['event'],
            subevent: $data['subevent'] ?? null,
            eventDate: $data['event_date'] ?? null,
            location: isset($data['location']) ? (object) $data['location'] : null,
        );
    }
}
