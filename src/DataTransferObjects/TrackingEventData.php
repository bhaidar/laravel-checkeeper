<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class TrackingEventData
{
    public function __construct(
        public string $event,
        public ?string $subevent = null,
        public ?string $eventDate = null,
        public ?string $eventDetails = null,
        public ?EventLocationData $eventLocation = null,
    ) {
    }

    public function toArray(): array
    {
        return array_filter([
            'event' => $this->event,
            'subevent' => $this->subevent,
            'event_date' => $this->eventDate,
            'event_details' => $this->eventDetails,
            'event_location' => $this->eventLocation?->toArray(),
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            event: $data['event'],
            subevent: $data['subevent'] ?? null,
            eventDate: $data['event_date'] ?? null,
            eventDetails: $data['event_details'] ?? null,
            eventLocation: isset($data['event_location'])
                ? EventLocationData::fromArray($data['event_location'])
                : null,
        );
    }
}
