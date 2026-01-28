<?php

use Bhaidar\Checkeeper\Facades\Checkeeper;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

test('can get team info', function () {
    Http::fake([
        '*/team/info' => Http::response([
            'data' => [
                'name' => 'Test Team',
                'credits' => 100,
            ],
        ], 200),
    ]);

    $info = Checkeeper::team()->info();

    expect($info)->toHaveKey('name')
        ->and($info['name'])->toBe('Test Team')
        ->and($info['credits'])->toBe(100);
});
