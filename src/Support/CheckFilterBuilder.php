<?php

namespace Bhaidar\Checkeeper\Support;

use Bhaidar\Checkeeper\Client\PendingRequest;
use Bhaidar\Checkeeper\DataTransferObjects\CheckStatusData;
use Illuminate\Support\Collection;

class CheckFilterBuilder
{
    protected array $filters = [];
    protected ?string $sortField = null;
    protected ?string $sortDirection = null;

    public function __construct(
        protected PendingRequest $request
    ) {
    }

    public function whereEquals(string $field, mixed $value): self
    {
        $this->filters["filters[{$field}][\$eq]"] = $value;

        return $this;
    }

    public function whereNotEquals(string $field, mixed $value): self
    {
        $this->filters["filters[{$field}][\$ne]"] = $value;

        return $this;
    }

    public function whereLessThan(string $field, mixed $value): self
    {
        $this->filters["filters[{$field}][\$lt]"] = $value;

        return $this;
    }

    public function whereLessThanOrEqual(string $field, mixed $value): self
    {
        $this->filters["filters[{$field}][\$lte]"] = $value;

        return $this;
    }

    public function whereGreaterThan(string $field, mixed $value): self
    {
        $this->filters["filters[{$field}][\$gt]"] = $value;

        return $this;
    }

    public function whereGreaterThanOrEqual(string $field, mixed $value): self
    {
        $this->filters["filters[{$field}][\$gte]"] = $value;

        return $this;
    }

    public function whereIn(string $field, array $values): self
    {
        $this->filters["filters[{$field}][\$in]"] = implode(',', $values);

        return $this;
    }

    public function whereNotIn(string $field, array $values): self
    {
        $this->filters["filters[{$field}][\$notIn]"] = implode(',', $values);

        return $this;
    }

    public function whereContains(string $field, string $value): self
    {
        $this->filters["filters[{$field}][\$contains]"] = $value;

        return $this;
    }

    public function whereBetween(string $field, mixed $start, mixed $end): self
    {
        $this->filters["filters[{$field}][\$between]"] = "{$start},{$end}";

        return $this;
    }

    public function sortBy(string $field, string $direction = 'asc'): self
    {
        $this->sortField = $field;
        $this->sortDirection = $direction;

        return $this;
    }

    public function get(): Collection
    {
        $params = $this->filters;

        if ($this->sortField) {
            $params['sort'] = "{$this->sortField}:{$this->sortDirection}";
        }

        $response = $this->request->get('/checks', $params);

        return collect($response->json('data', []))
            ->map(fn (array $check) => CheckStatusData::fromArray($check));
    }

    public function toArray(): array
    {
        $params = $this->filters;

        if ($this->sortField) {
            $params['sort'] = "{$this->sortField}:{$this->sortDirection}";
        }

        return $params;
    }
}
