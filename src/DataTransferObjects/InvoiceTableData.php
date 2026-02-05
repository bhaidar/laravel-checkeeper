<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class InvoiceTableData
{
    /**
     * @param array<int, InvoiceHeadingData> $headings
     * @param array<int, array<int, string>> $rows
     */
    public function __construct(
        public array $headings,
        public array $rows,
    ) {
    }

    public function toArray(): array
    {
        return [
            'headings' => array_map(
                fn (InvoiceHeadingData $heading) => $heading->toArray(),
                $this->headings,
            ),
            'rows' => $this->rows,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            headings: array_map(
                fn (array $heading) => InvoiceHeadingData::fromArray($heading),
                $data['headings'],
            ),
            rows: $data['rows'],
        );
    }
}
