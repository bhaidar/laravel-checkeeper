<?php

use Bhaidar\Checkeeper\DataTransferObjects\AddressData;
use Bhaidar\Checkeeper\DataTransferObjects\BankData;
use Bhaidar\Checkeeper\DataTransferObjects\CheckData;
use Bhaidar\Checkeeper\DataTransferObjects\CheckStatusData;
use Bhaidar\Checkeeper\DataTransferObjects\DeductionData;
use Bhaidar\Checkeeper\DataTransferObjects\EarningData;
use Bhaidar\Checkeeper\DataTransferObjects\EmployeeData;
use Bhaidar\Checkeeper\DataTransferObjects\EventLocationData;
use Bhaidar\Checkeeper\DataTransferObjects\InvoiceHeadingData;
use Bhaidar\Checkeeper\DataTransferObjects\InvoiceTableData;
use Bhaidar\Checkeeper\DataTransferObjects\OtherPaystubData;
use Bhaidar\Checkeeper\DataTransferObjects\PayeeData;
use Bhaidar\Checkeeper\DataTransferObjects\PayerData;
use Bhaidar\Checkeeper\DataTransferObjects\PayPeriodData;
use Bhaidar\Checkeeper\DataTransferObjects\PaystubData;
use Bhaidar\Checkeeper\DataTransferObjects\PayYtdData;
use Bhaidar\Checkeeper\DataTransferObjects\SignerData;
use Bhaidar\Checkeeper\DataTransferObjects\TrackingEventData;
use Bhaidar\Checkeeper\DataTransferObjects\WithholdingsData;
use Bhaidar\Checkeeper\Enums\CheckStatus;
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

test('CheckData serializes template field correctly', function () {
    $checkData = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 50000,
        number: 1001,
        template: 'CheckOnTop',
    );

    $array = $checkData->toArray();

    expect($array)->toHaveKey('template')
        ->and($array['template'])->toBe('CheckOnTop')
        ->and($array)->not->toHaveKey('template_id');
});

test('CheckData serializes special_instructions and voucher_image', function () {
    $checkData = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 50000,
        number: 1001,
        specialInstructions: 'Void after 90 days',
        voucherImage: 'base64encodedimage',
    );

    $array = $checkData->toArray();

    expect($array['special_instructions'])->toBe('Void after 90 days')
        ->and($array['voucher_image'])->toBe('base64encodedimage');
});

test('CheckData roundtrips special_instructions and voucher_image', function () {
    $array = [
        'bank' => ['routing' => '123456789', 'account' => '987654321'],
        'payer' => ['line1' => 'My Company'],
        'payee' => ['line1' => 'Vendor LLC'],
        'signer' => ['type' => 'text', 'value' => 'John Doe'],
        'amount' => 50000,
        'number' => 1001,
        'special_instructions' => 'Payable in US funds',
        'voucher_image' => 'base64data',
    ];

    $checkData = CheckData::fromArray($array);

    expect($checkData->specialInstructions)->toBe('Payable in US funds')
        ->and($checkData->voucherImage)->toBe('base64data');
});

test('InvoiceHeadingData can convert to array', function () {
    $heading = new InvoiceHeadingData('Invoice Number', 20);

    $array = $heading->toArray();

    expect($array['label'])->toBe('Invoice Number')
        ->and($array['size'])->toBe(20);
});

test('InvoiceHeadingData omits null size', function () {
    $heading = new InvoiceHeadingData('Description');

    $array = $heading->toArray();

    expect($array)->toHaveKey('label')
        ->and($array)->not->toHaveKey('size');
});

test('InvoiceTableData can convert to array and back', function () {
    $table = new InvoiceTableData(
        headings: [
            new InvoiceHeadingData('Invoice Number', 20),
            new InvoiceHeadingData('Description', 60),
            new InvoiceHeadingData('Amount', 20),
        ],
        rows: [
            ['Inv 345', 'Widget Cranks', '34.95'],
            ['Inv 385', 'Widget Screens', '137.62'],
        ],
    );

    $array = $table->toArray();

    expect($array['headings'])->toHaveCount(3)
        ->and($array['headings'][0]['label'])->toBe('Invoice Number')
        ->and($array['headings'][1]['size'])->toBe(60)
        ->and($array['rows'])->toHaveCount(2)
        ->and($array['rows'][0][0])->toBe('Inv 345');

    $restored = InvoiceTableData::fromArray($array);

    expect($restored->headings)->toHaveCount(3)
        ->and($restored->headings[0]->label)->toBe('Invoice Number')
        ->and($restored->rows[1][2])->toBe('137.62');
});

test('CheckData serializes invoice_table', function () {
    $checkData = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 50000,
        number: 1001,
        invoiceTable: new InvoiceTableData(
            headings: [new InvoiceHeadingData('Amount', 100)],
            rows: [['500.00']],
        ),
    );

    $array = $checkData->toArray();

    expect($array)->toHaveKey('invoice_table')
        ->and($array['invoice_table']['headings'][0]['label'])->toBe('Amount');
});

test('PayPeriodData can convert to array and back', function () {
    $period = new PayPeriodData('1576.26', '1295.34', '2024-01-26', '2024-02-09');

    $array = $period->toArray();

    expect($array['gross'])->toBe('1576.26')
        ->and($array['net'])->toBe('1295.34')
        ->and($array['starting'])->toBe('2024-01-26')
        ->and($array['ending'])->toBe('2024-02-09');

    $restored = PayPeriodData::fromArray($array);

    expect($restored->gross)->toBe('1576.26');
});

test('PayYtdData can convert to array and back', function () {
    $ytd = new PayYtdData('3500.00', '2590.68');

    $array = $ytd->toArray();

    expect($array['gross'])->toBe('3500.00')
        ->and($array['net'])->toBe('2590.68');

    $restored = PayYtdData::fromArray($array);

    expect($restored->gross)->toBe('3500.00');
});

test('WithholdingsData can convert to array and back', function () {
    $withholdings = new WithholdingsData('3', '1', '0');

    $array = $withholdings->toArray();

    expect($array['federal'])->toBe('3')
        ->and($array['state'])->toBe('1')
        ->and($array['local'])->toBe('0');

    $restored = WithholdingsData::fromArray($array);

    expect($restored->federal)->toBe('3');
});

test('EmployeeData can convert to array and back', function () {
    $employee = new EmployeeData(
        social: '123-45-6789',
        status: 'Married, Filing Jointly',
        withholdings: new WithholdingsData('3', '1', '0'),
    );

    $array = $employee->toArray();

    expect($array['social'])->toBe('123-45-6789')
        ->and($array['withholdings']['federal'])->toBe('3');

    $restored = EmployeeData::fromArray($array);

    expect($restored->social)->toBe('123-45-6789')
        ->and($restored->withholdings->federal)->toBe('3');
});

test('EarningData can convert to array and back', function () {
    $earning = new EarningData('Regular Pay', '17.52', '40', '700.80', '1401.60');

    $array = $earning->toArray();

    expect($array['type'])->toBe('Regular Pay')
        ->and($array['rate'])->toBe('17.52')
        ->and($array['hours'])->toBe('40')
        ->and($array['period'])->toBe('700.80')
        ->and($array['ytd'])->toBe('1401.60');

    $restored = EarningData::fromArray($array);

    expect($restored->type)->toBe('Regular Pay');
});

test('DeductionData can convert to array and back', function () {
    $deduction = new DeductionData('Federal Income Tax', '-40.50', '81.00');

    $array = $deduction->toArray();

    expect($array['type'])->toBe('Federal Income Tax')
        ->and($array['period'])->toBe('-40.50')
        ->and($array['ytd'])->toBe('81.00');

    $restored = DeductionData::fromArray($array);

    expect($restored->type)->toBe('Federal Income Tax');
});

test('OtherPaystubData can convert to array and back', function () {
    $other = new OtherPaystubData('Group Term Life', '0.51', '1.02');

    $array = $other->toArray();

    expect($array['type'])->toBe('Group Term Life')
        ->and($array['period'])->toBe('0.51');

    $restored = OtherPaystubData::fromArray($array);

    expect($restored->type)->toBe('Group Term Life');
});

test('PaystubData can convert to array and back', function () {
    $paystub = new PaystubData(
        period: new PayPeriodData('1576.26', '1295.34', '2024-01-26', '2024-02-09'),
        ytd: new PayYtdData('3500.00', '2590.68'),
        employee: new EmployeeData(
            social: '123-45-6789',
            status: 'Single',
            withholdings: new WithholdingsData('3', '1', '0'),
        ),
        note: 'Rate changed this period.',
        earnings: [new EarningData('Regular Pay', '17.52', '40', '700.80', '1401.60')],
        deductions: [new DeductionData('Federal Income Tax', '-40.50', '81.00')],
        other: [new OtherPaystubData('Group Term Life', '0.51', '1.02')],
    );

    $array = $paystub->toArray();

    expect($array['period']['gross'])->toBe('1576.26')
        ->and($array['ytd']['net'])->toBe('2590.68')
        ->and($array['employee']['social'])->toBe('123-45-6789')
        ->and($array['note'])->toBe('Rate changed this period.')
        ->and($array['earnings'])->toHaveCount(1)
        ->and($array['earnings'][0]['type'])->toBe('Regular Pay')
        ->and($array['deductions'][0]['type'])->toBe('Federal Income Tax')
        ->and($array['other'][0]['type'])->toBe('Group Term Life');

    $restored = PaystubData::fromArray($array);

    expect($restored->period->gross)->toBe('1576.26')
        ->and($restored->earnings)->toHaveCount(1)
        ->and($restored->earnings[0]->type)->toBe('Regular Pay')
        ->and($restored->deductions[0]->period)->toBe('-40.50')
        ->and($restored->other[0]->ytd)->toBe('1.02');
});

test('PaystubData omits null sections', function () {
    $paystub = new PaystubData(
        period: new PayPeriodData('1000.00', '800.00'),
    );

    $array = $paystub->toArray();

    expect($array)->toHaveKey('period')
        ->and($array)->not->toHaveKey('ytd')
        ->and($array)->not->toHaveKey('employee')
        ->and($array)->not->toHaveKey('earnings');
});

test('CheckData serializes paystub', function () {
    $checkData = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 50000,
        number: 1001,
        paystub: new PaystubData(
            period: new PayPeriodData('1000.00', '800.00', '2024-01-01', '2024-01-15'),
        ),
    );

    $array = $checkData->toArray();

    expect($array)->toHaveKey('paystub')
        ->and($array['paystub']['period']['gross'])->toBe('1000.00');
});

test('CheckData roundtrips invoice_table and paystub from array', function () {
    $array = [
        'bank' => ['routing' => '123456789', 'account' => '987654321'],
        'payer' => ['line1' => 'My Company'],
        'payee' => ['line1' => 'Vendor LLC'],
        'signer' => ['type' => 'text', 'value' => 'John Doe'],
        'amount' => 50000,
        'number' => 1001,
        'invoice_table' => [
            'headings' => [['label' => 'Amount', 'size' => 100]],
            'rows' => [['500.00']],
        ],
        'paystub' => [
            'period' => ['gross' => '1000.00', 'net' => '800.00'],
            'note' => 'Test note',
        ],
    ];

    $checkData = CheckData::fromArray($array);

    expect($checkData->invoiceTable)->toBeInstanceOf(InvoiceTableData::class)
        ->and($checkData->invoiceTable->headings[0]->label)->toBe('Amount')
        ->and($checkData->paystub)->toBeInstanceOf(PaystubData::class)
        ->and($checkData->paystub->period->gross)->toBe('1000.00')
        ->and($checkData->paystub->note)->toBe('Test note');
});

test('CheckStatusData includes all response fields', function () {
    $data = [
        'id' => 'check-123',
        'status' => 'mailed',
        'request_id' => 'req-456',
        'test' => false,
        'created' => '2024-01-01 10:00:00',
        'updated' => '2024-01-02 10:00:00',
        'printed' => '2024-01-01 12:00:00',
        'mailed' => '2024-01-02 08:00:00',
        'delivery_method' => 'usps.first_class',
        'tracking_number' => '9400111899223100001234',
        'tracking_url' => 'https://tracking.example.com',
        'meta' => ['internal_id' => '123'],
    ];

    $status = CheckStatusData::fromArray($data);

    expect($status->id)->toBe('check-123')
        ->and($status->status)->toBe(CheckStatus::Mailed)
        ->and($status->requestId)->toBe('req-456')
        ->and($status->test)->toBeFalse()
        ->and($status->created)->toBe('2024-01-01 10:00:00')
        ->and($status->printed)->toBe('2024-01-01 12:00:00')
        ->and($status->mailed)->toBe('2024-01-02 08:00:00')
        ->and($status->deliveryMethod)->toBe('usps.first_class')
        ->and($status->trackingNumber)->toBe('9400111899223100001234')
        ->and($status->trackingUrl)->toBe('https://tracking.example.com')
        ->and($status->meta)->toBe(['internal_id' => '123']);
});

test('CheckStatusData toArray includes all fields', function () {
    $status = new CheckStatusData(
        id: 'check-123',
        status: CheckStatus::Delivered,
        requestId: 'req-456',
        test: true,
        printed: '2024-01-01 12:00:00',
        mailed: '2024-01-02 08:00:00',
        deliveryMethod: 'usps.first_class',
        trackingNumber: '940011189922',
    );

    $array = $status->toArray();

    expect($array['id'])->toBe('check-123')
        ->and($array['status'])->toBe('delivered')
        ->and($array['request_id'])->toBe('req-456')
        ->and($array['test'])->toBeTrue()
        ->and($array['printed'])->toBe('2024-01-01 12:00:00')
        ->and($array['delivery_method'])->toBe('usps.first_class')
        ->and($array['tracking_number'])->toBe('940011189922');
});

test('EventLocationData can convert to array and back', function () {
    $location = new EventLocationData('US', '29601', 'SC', 'Greenville');

    $array = $location->toArray();

    expect($array['country'])->toBe('US')
        ->and($array['zip'])->toBe('29601')
        ->and($array['state'])->toBe('SC')
        ->and($array['city'])->toBe('Greenville');

    $restored = EventLocationData::fromArray($array);

    expect($restored->country)->toBe('US')
        ->and($restored->city)->toBe('Greenville');
});

test('TrackingEventData includes event_details and event_location', function () {
    $data = [
        'event' => 'TRANSIT',
        'subevent' => 'IN_TRANSIT',
        'event_date' => '2024-01-02 10:00:00',
        'event_details' => 'Package in transit',
        'event_location' => [
            'country' => 'US',
            'zip' => '29601',
            'state' => 'SC',
            'city' => 'Greenville',
        ],
    ];

    $tracking = TrackingEventData::fromArray($data);

    expect($tracking->event)->toBe('TRANSIT')
        ->and($tracking->eventDetails)->toBe('Package in transit')
        ->and($tracking->eventLocation)->toBeInstanceOf(EventLocationData::class)
        ->and($tracking->eventLocation->city)->toBe('Greenville');

    $array = $tracking->toArray();

    expect($array['event_details'])->toBe('Package in transit')
        ->and($array['event_location']['city'])->toBe('Greenville');
});

test('TrackingEventData handles missing optional fields', function () {
    $data = ['event' => 'DELIVERED'];

    $tracking = TrackingEventData::fromArray($data);

    expect($tracking->event)->toBe('DELIVERED')
        ->and($tracking->subevent)->toBeNull()
        ->and($tracking->eventDetails)->toBeNull()
        ->and($tracking->eventLocation)->toBeNull();

    $array = $tracking->toArray();

    expect($array)->toHaveKey('event')
        ->and($array)->not->toHaveKey('event_details')
        ->and($array)->not->toHaveKey('event_location');
});

test('CheckStatus enum includes all API statuses', function () {
    expect(CheckStatus::Processing->value)->toBe('processing')
        ->and(CheckStatus::Ready->value)->toBe('ready')
        ->and(CheckStatus::Printed->value)->toBe('printed')
        ->and(CheckStatus::Mailed->value)->toBe('mailed')
        ->and(CheckStatus::PreTransit->value)->toBe('pre_transit')
        ->and(CheckStatus::Transit->value)->toBe('transit')
        ->and(CheckStatus::Delivery->value)->toBe('delivery')
        ->and(CheckStatus::Delivered->value)->toBe('delivered')
        ->and(CheckStatus::Cancelled->value)->toBe('cancelled')
        ->and(CheckStatus::Returned->value)->toBe('returned')
        ->and(CheckStatus::PdfReturned->value)->toBe('pdf returned');
});

test('CheckData full roundtrip with all fields', function () {
    $original = new CheckData(
        bank: new BankData('123456789', '987654321'),
        payer: new PayerData('My Company', '123 Main St', 'Suite 100'),
        payee: new PayeeData('Vendor LLC'),
        signer: new SignerData(SignerType::Text, 'John Doe'),
        amount: 12500,
        number: 5008,
        date: '2024-02-29',
        memo: 'Payment for widgets',
        nonce: 'unique-123',
        template: 'CheckOnTop',
        fromAddress: new AddressData(
            name: 'Bob Owner',
            line1: '101 North Main',
            city: 'Greenville',
            state: 'SC',
            zip: '90210',
        ),
        toAddress: new AddressData(
            name: 'Vendor Inc',
            line1: '3776 Washington St',
            city: 'Winnebago',
            state: 'MN',
            zip: '57002',
        ),
        meta: ['internal_id' => '23512'],
        test: true,
        specialInstructions: 'Void after 90 days',
        voucherImage: 'base64image',
        invoiceTable: new InvoiceTableData(
            headings: [
                new InvoiceHeadingData('Invoice', 30),
                new InvoiceHeadingData('Amount', 70),
            ],
            rows: [['Inv 345', '34.95']],
        ),
        paystub: new PaystubData(
            period: new PayPeriodData('1576.26', '1295.34', '2024-01-26', '2024-02-09'),
            ytd: new PayYtdData('3500.00', '2590.68'),
            employee: new EmployeeData('123-45-6789', 'Single', new WithholdingsData('3', '1', '0')),
            note: 'Rate changed',
            earnings: [new EarningData('Regular Pay', '17.52', '40', '700.80', '1401.60')],
            deductions: [new DeductionData('Federal Tax', '-40.50', '81.00')],
            other: [new OtherPaystubData('Life Insurance', '0.51', '1.02')],
        ),
    );

    $array = $original->toArray();
    $restored = CheckData::fromArray($array);

    expect($restored->amount)->toBe(12500)
        ->and($restored->template)->toBe('CheckOnTop')
        ->and($restored->specialInstructions)->toBe('Void after 90 days')
        ->and($restored->voucherImage)->toBe('base64image')
        ->and($restored->invoiceTable->headings)->toHaveCount(2)
        ->and($restored->invoiceTable->rows[0][1])->toBe('34.95')
        ->and($restored->paystub->period->gross)->toBe('1576.26')
        ->and($restored->paystub->earnings[0]->rate)->toBe('17.52')
        ->and($restored->paystub->deductions[0]->type)->toBe('Federal Tax')
        ->and($restored->paystub->other[0]->type)->toBe('Life Insurance')
        ->and($restored->fromAddress->city)->toBe('Greenville')
        ->and($restored->toAddress->state)->toBe('MN');
});
