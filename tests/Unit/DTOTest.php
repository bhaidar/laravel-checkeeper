<?php

use Bhaidar\Checkeeper\DataTransferObjects\BankData;
use Bhaidar\Checkeeper\DataTransferObjects\CheckData;
use Bhaidar\Checkeeper\DataTransferObjects\PayeeData;
use Bhaidar\Checkeeper\DataTransferObjects\PayerData;
use Bhaidar\Checkeeper\DataTransferObjects\SignerData;
use Bhaidar\Checkeeper\Enums\SignerType;

test('BankData can convert to array', function () {
    $bank = new BankData('123456789', '987654321');

    $array = $bank->toArray();

    expect($array)->toHaveKey('routing')
        ->and($array['routing'])->toBe('123456789')
        ->and($array['account'])->toBe('987654321');
});

test('BankData can create from array', function () {
    $array = ['routing' => '123456789', 'account' => '987654321'];

    $bank = BankData::fromArray($array);

    expect($bank->routing)->toBe('123456789')
        ->and($bank->account)->toBe('987654321');
});

test('SignerData can convert to array', function () {
    $signer = new SignerData(SignerType::Text, 'John Doe');

    $array = $signer->toArray();

    expect($array)->toHaveKey('type')
        ->and($array['type'])->toBe('text')
        ->and($array['value'])->toBe('John Doe');
});

test('PayerData filters null values', function () {
    $payer = new PayerData('My Company', 'Address Line 2');

    $array = $payer->toArray();

    expect($array)->toHaveKey('line1')
        ->and($array)->toHaveKey('line2')
        ->and($array)->not->toHaveKey('line3');
});

test('CheckData can convert to array', function () {
    $checkData = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 50000,
        number: 1001,
        memo: 'Invoice #12345'
    );

    $array = $checkData->toArray();

    expect($array)->toHaveKey('bank')
        ->and($array)->toHaveKey('payer')
        ->and($array)->toHaveKey('payee')
        ->and($array)->toHaveKey('signer')
        ->and($array)->toHaveKey('amount')
        ->and($array)->toHaveKey('number')
        ->and($array)->toHaveKey('memo')
        ->and($array['amount'])->toBe(50000)
        ->and($array['memo'])->toBe('Invoice #12345');
});

test('CheckData can create from array', function () {
    $array = [
        'bank' => ['routing' => '123456789', 'account' => '987654321'],
        'payer' => ['line1' => 'My Company'],
        'payee' => ['line1' => 'Vendor LLC'],
        'signer' => ['type' => 'text', 'value' => 'John Doe'],
        'amount' => 50000,
        'number' => 1001,
        'memo' => 'Invoice #12345',
    ];

    $checkData = CheckData::fromArray($array);

    expect($checkData->amount)->toBe(50000)
        ->and($checkData->number)->toBe(1001)
        ->and($checkData->memo)->toBe('Invoice #12345')
        ->and($checkData->bank->routing)->toBe('123456789');
});
