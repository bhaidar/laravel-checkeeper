<?php

use Bhaidar\Checkeeper\Client\PendingRequest;
use Bhaidar\Checkeeper\Support\CheckFilterBuilder;
use Illuminate\Http\Client\PendingRequest as LaravelPendingRequest;
use Illuminate\Support\Facades\Http;

test('can build equals filter', function () {
    $laravelRequest = Http::baseUrl('https://api.checkeeper.com/v3')->acceptJson();
    $request = new PendingRequest($laravelRequest);
    $builder = new CheckFilterBuilder($request);

    $params = $builder->whereEquals('status', 'delivered')->toArray();

    expect($params)->toHaveKey('filters[status][$eq]')
        ->and($params['filters[status][$eq]'])->toBe('delivered');
});

test('can build greater than filter', function () {
    $laravelRequest = Http::baseUrl('https://api.checkeeper.com/v3')->acceptJson();
    $request = new PendingRequest($laravelRequest);
    $builder = new CheckFilterBuilder($request);

    $params = $builder->whereGreaterThan('amount', 10000)->toArray();

    expect($params)->toHaveKey('filters[amount][$gt]')
        ->and($params['filters[amount][$gt]'])->toBe(10000);
});

test('can build between filter', function () {
    $laravelRequest = Http::baseUrl('https://api.checkeeper.com/v3')->acceptJson();
    $request = new PendingRequest($laravelRequest);
    $builder = new CheckFilterBuilder($request);

    $params = $builder->whereBetween('date', '2024-01-01', '2024-12-31')->toArray();

    expect($params)->toHaveKey('filters[date][$between]')
        ->and($params['filters[date][$between]'])->toBe('2024-01-01,2024-12-31');
});

test('can build in filter', function () {
    $laravelRequest = Http::baseUrl('https://api.checkeeper.com/v3')->acceptJson();
    $request = new PendingRequest($laravelRequest);
    $builder = new CheckFilterBuilder($request);

    $params = $builder->whereIn('status', ['delivered', 'printed'])->toArray();

    expect($params)->toHaveKey('filters[status][$in]')
        ->and($params['filters[status][$in]'])->toBe('delivered,printed');
});

test('can build sort', function () {
    $laravelRequest = Http::baseUrl('https://api.checkeeper.com/v3')->acceptJson();
    $request = new PendingRequest($laravelRequest);
    $builder = new CheckFilterBuilder($request);

    $params = $builder->sortBy('created', 'desc')->toArray();

    expect($params)->toHaveKey('sort')
        ->and($params['sort'])->toBe('created:desc');
});

test('can chain multiple filters', function () {
    $laravelRequest = Http::baseUrl('https://api.checkeeper.com/v3')->acceptJson();
    $request = new PendingRequest($laravelRequest);
    $builder = new CheckFilterBuilder($request);

    $params = $builder
        ->whereEquals('status', 'delivered')
        ->whereGreaterThan('amount', 10000)
        ->sortBy('created', 'desc')
        ->toArray();

    expect($params)->toHaveKey('filters[status][$eq]')
        ->and($params)->toHaveKey('filters[amount][$gt]')
        ->and($params)->toHaveKey('sort');
});
