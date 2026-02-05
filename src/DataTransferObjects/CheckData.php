<?php

namespace Bhaidar\Checkeeper\DataTransferObjects;

readonly class CheckData
{
    public function __construct(
        public BankData $bank,
        public PayerData $payer,
        public PayeeData $payee,
        public SignerData $signer,
        public int $amount,
        public int $number,
        public ?string $date = null,
        public ?string $memo = null,
        public ?string $note = null,
        public ?string $nonce = null,
        public ?string $templateId = null,
        public ?DeliveryData $delivery = null,
        public ?AddressData $fromAddress = null,
        public ?AddressData $toAddress = null,
        public ?array $attachments = null,
        public ?array $meta = null,
        public ?bool $test = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'bank' => $this->bank->toArray(),
            'payer' => $this->payer->toArray(),
            'payee' => $this->payee->toArray(),
            'signer' => $this->signer->toArray(),
            'amount' => $this->amount,
            'number' => $this->number,
        ];

        if ($this->date !== null) {
            $data['date'] = $this->date;
        }

        if ($this->memo !== null) {
            $data['memo'] = $this->memo;
        }

        if ($this->note !== null) {
            $data['note'] = $this->note;
        }

        if ($this->nonce !== null) {
            $data['nonce'] = $this->nonce;
        }

        if ($this->templateId !== null) {
            $data['template_id'] = $this->templateId;
        }

        if ($this->delivery !== null) {
            $data['delivery'] = $this->delivery->toArray();
        }

        if ($this->fromAddress !== null) {
            $data['from_address'] = $this->fromAddress->toArray();
        }

        if ($this->toAddress !== null) {
            $data['to_address'] = $this->toAddress->toArray();
        }

        if ($this->attachments !== null) {
            $data['attachments'] = $this->attachments;
        }

        if ($this->meta !== null) {
            $data['meta'] = $this->meta;
        }

        if ($this->test !== null) {
            $data['test'] = $this->test;
        }

        return $data;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            bank: BankData::fromArray($data['bank']),
            payer: PayerData::fromArray($data['payer']),
            payee: PayeeData::fromArray($data['payee']),
            signer: SignerData::fromArray($data['signer']),
            amount: $data['amount'],
            number: $data['number'],
            date: $data['date'] ?? null,
            memo: $data['memo'] ?? null,
            note: $data['note'] ?? null,
            nonce: $data['nonce'] ?? null,
            templateId: $data['template_id'] ?? null,
            delivery: isset($data['delivery'])
                ? DeliveryData::fromArray($data['delivery'])
                : null,
            fromAddress: isset($data['from_address'])
                ? AddressData::fromArray($data['from_address'])
                : null,
            toAddress: isset($data['to_address'])
                ? AddressData::fromArray($data['to_address'])
                : null,
            attachments: $data['attachments'] ?? null,
            meta: $data['meta'] ?? null,
            test: $data['test'] ?? null,
        );
    }
}
