<?php

namespace Bhaidar\Checkeeper\Resources;

use Bhaidar\Checkeeper\Client\PendingRequest;
use Bhaidar\Checkeeper\DataTransferObjects\CheckData;
use Bhaidar\Checkeeper\DataTransferObjects\CheckStatusData;
use Bhaidar\Checkeeper\DataTransferObjects\TrackingEventData;
use Bhaidar\Checkeeper\Support\CheckFilterBuilder;
use Illuminate\Support\Collection;

class CheckResource
{
    public function __construct(
        protected PendingRequest $request
    ) {
    }

    public function list(array $filters = []): Collection
    {
        $response = $this->request->get('/checks', $filters);

        return collect($response->json('data', []))
            ->map(fn (array $check) => CheckStatusData::fromArray($check));
    }

    public function create(CheckData|array $data): CheckStatusData
    {
        $payload = $data instanceof CheckData ? $data->toArray() : $data;

        $response = $this->request->post('/check', ['checks' => [$payload]]);

        $checks = $response->json('data.checks', []);

        return CheckStatusData::fromArray($checks[0]);
    }

    public function createBulk(array $checks): array
    {
        $payload = array_map(
            fn ($check) => $check instanceof CheckData ? $check->toArray() : $check,
            $checks
        );

        $response = $this->request->post('/check', ['checks' => $payload]);

        return [
            'checks' => collect($response->json('data.checks', []))
                ->map(fn (array $check) => CheckStatusData::fromArray($check))
                ->all(),
            'existing' => $response->json('data.existing', []),
            'total_credits' => $response->json('data.total_credits', 0),
        ];
    }

    public function status(string $id): CheckStatusData
    {
        $response = $this->request->get("/check/{$id}/status");

        return CheckStatusData::fromArray($response->json('data', []));
    }

    public function tracking(string $id): Collection
    {
        $response = $this->request->get("/check/{$id}/tracking");

        return collect($response->json('data', []))
            ->map(fn (array $event) => TrackingEventData::fromArray($event));
    }

    public function cancel(string $id): bool
    {
        $response = $this->request->post("/check/{$id}/cancel");

        return $response->successful();
    }

    public function image(string $id, string $type = 'jpg'): string
    {
        $response = $this->request->get("/check/{$id}/image", ['type' => $type]);

        return $response->body();
    }

    public function attachment(string $id, string $file): bool
    {
        $response = $this->request->post("/check/{$id}/attachment", [
            'file' => $file,
        ]);

        return $response->successful();
    }

    public function voucherImage(string $id): string
    {
        $response = $this->request->get("/check/{$id}/voucher/image");

        return $response->body();
    }

    public function filter(): CheckFilterBuilder
    {
        return new CheckFilterBuilder($this->request);
    }
}
