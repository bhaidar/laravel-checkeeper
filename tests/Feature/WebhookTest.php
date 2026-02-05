<?php

use Bhaidar\Checkeeper\Events\WebhookReceived;
use Illuminate\Support\Facades\Event;

test('webhook endpoint validates signature', function () {
    $payload = json_encode(['event' => 'check.delivered', 'check_id' => 'check-123']);
    $secret = config('checkeeper.webhooks.secret');
    $signature = hash_hmac('sha256', $payload, $secret);

    $response = $this->postJson('/checkeeper/webhook', json_decode($payload, true), [
        'X-Checkeeper-Signature' => $signature,
    ]);

    $response->assertStatus(200);
});

test('webhook endpoint rejects invalid signature', function () {
    $payload = ['event' => 'check.delivered', 'check_id' => 'check-123'];

    $response = $this->postJson('/checkeeper/webhook', $payload, [
        'X-Checkeeper-Signature' => 'invalid-signature',
    ]);

    $response->assertStatus(401);
});

test('webhook dispatches event', function () {
    Event::fake();

    $payload = json_encode(['event' => 'check.delivered', 'check_id' => 'check-123']);
    $secret = config('checkeeper.webhooks.secret');
    $signature = hash_hmac('sha256', $payload, $secret);

    $this->postJson('/checkeeper/webhook', json_decode($payload, true), [
        'X-Checkeeper-Signature' => $signature,
    ]);

    Event::assertDispatched(WebhookReceived::class);
});
