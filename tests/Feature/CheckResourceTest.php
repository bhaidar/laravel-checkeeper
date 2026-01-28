<?php

use Bhaidar\Checkeeper\DataTransferObjects\BankData;
use Bhaidar\Checkeeper\DataTransferObjects\CheckData;
use Bhaidar\Checkeeper\DataTransferObjects\PayeeData;
use Bhaidar\Checkeeper\DataTransferObjects\PayerData;
use Bhaidar\Checkeeper\DataTransferObjects\SignerData;
use Bhaidar\Checkeeper\Enums\CheckStatus;
use Bhaidar\Checkeeper\Enums\SignerType;
use Bhaidar\Checkeeper\Facades\Checkeeper;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

test('can list checks', function () {
    Http::fake([
        '*/checks*' => Http::response([
            'data' => [
                [
                    'id' => 'check-123',
                    'status' => 'processing',
                    'created' => '2024-01-01 10:00:00',
                ],
            ],
        ], 200),
    ]);

    $checks = Checkeeper::checks()->list();

    expect($checks)->toHaveCount(1)
        ->and($checks->first()->id)->toBe('check-123')
        ->and($checks->first()->status)->toBe(CheckStatus::Processing);
});

test('can create check', function () {
    Http::fake([
        '*/check' => Http::response([
            'data' => [
                'checks' => [
                    [
                        'id' => 'check-123',
                        'status' => 'processing',
                    ],
                ],
            ],
        ], 201),
    ]);

    $checkData = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 50000,
        number: 1001
    );

    $result = Checkeeper::checks()->create($checkData);

    expect($result->id)->toBe('check-123')
        ->and($result->status)->toBe(CheckStatus::Processing);
});

test('can get check status', function () {
    Http::fake([
        '*/check/check-123/status' => Http::response([
            'data' => [
                'id' => 'check-123',
                'status' => 'delivered',
                'tracking_url' => 'https://tracking.example.com',
            ],
        ], 200),
    ]);

    $status = Checkeeper::checks()->status('check-123');

    expect($status->id)->toBe('check-123')
        ->and($status->status)->toBe(CheckStatus::Delivered)
        ->and($status->trackingUrl)->toBe('https://tracking.example.com');
});

test('can get tracking events', function () {
    Http::fake([
        '*/check/check-123/tracking' => Http::response([
            'data' => [
                [
                    'event' => 'TRANSIT',
                    'event_date' => '2024-01-02 10:00:00',
                ],
                [
                    'event' => 'DELIVERY',
                    'event_date' => '2024-01-03 14:00:00',
                ],
            ],
        ], 200),
    ]);

    $events = Checkeeper::checks()->tracking('check-123');

    expect($events)->toHaveCount(2)
        ->and($events->first()->event)->toBe('TRANSIT')
        ->and($events->last()->event)->toBe('DELIVERY');
});

test('can cancel check', function () {
    Http::fake([
        '*/check/check-123/cancel' => Http::response(['message' => 'Check cancelled'], 200),
    ]);

    $cancelled = Checkeeper::checks()->cancel('check-123');

    expect($cancelled)->toBeTrue();
});

test('can filter checks', function () {
    Http::fake([
        '*/checks*' => Http::response([
            'data' => [
                [
                    'id' => 'check-123',
                    'status' => 'delivered',
                ],
            ],
        ], 200),
    ]);

    $checks = Checkeeper::checks()
        ->filter()
        ->whereEquals('status', 'delivered')
        ->whereGreaterThan('amount', 10000)
        ->get();

    expect($checks)->toHaveCount(1)
        ->and($checks->first()->status)->toBe(CheckStatus::Delivered);
});
