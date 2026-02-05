<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class PaystubData
{
    /**
     * @param ?PayPeriodData $period
     * @param ?PayYtdData $ytd
     * @param ?EmployeeData $employee
     * @param ?string $note
     * @param ?array<int, EarningData> $earnings
     * @param ?array<int, DeductionData> $deductions
     * @param ?array<int, OtherPaystubData> $other
     */
    public function __construct(
        public ?PayPeriodData $period = null,
        public ?PayYtdData $ytd = null,
        public ?EmployeeData $employee = null,
        public ?string $note = null,
        public ?array $earnings = null,
        public ?array $deductions = null,
        public ?array $other = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->period !== null) {
            $data['period'] = $this->period->toArray();
        }

        if ($this->ytd !== null) {
            $data['ytd'] = $this->ytd->toArray();
        }

        if ($this->employee !== null) {
            $data['employee'] = $this->employee->toArray();
        }

        if ($this->note !== null) {
            $data['note'] = $this->note;
        }

        if ($this->earnings !== null) {
            $data['earnings'] = array_map(
                fn (EarningData $earning) => $earning->toArray(),
                $this->earnings,
            );
        }

        if ($this->deductions !== null) {
            $data['deductions'] = array_map(
                fn (DeductionData $deduction) => $deduction->toArray(),
                $this->deductions,
            );
        }

        if ($this->other !== null) {
            $data['other'] = array_map(
                fn (OtherPaystubData $other) => $other->toArray(),
                $this->other,
            );
        }

        return $data;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period: isset($data['period'])
                ? PayPeriodData::fromArray($data['period'])
                : null,
            ytd: isset($data['ytd'])
                ? PayYtdData::fromArray($data['ytd'])
                : null,
            employee: isset($data['employee'])
                ? EmployeeData::fromArray($data['employee'])
                : null,
            note: $data['note'] ?? null,
            earnings: isset($data['earnings'])
                ? array_map(fn (array $e) => EarningData::fromArray($e), $data['earnings'])
                : null,
            deductions: isset($data['deductions'])
                ? array_map(fn (array $d) => DeductionData::fromArray($d), $data['deductions'])
                : null,
            other: isset($data['other'])
                ? array_map(fn (array $o) => OtherPaystubData::fromArray($o), $data['other'])
                : null,
        );
    }
}
