<?php

namespace Bhaidar\Checkeeper\Http\Controllers;

use Bhaidar\Checkeeper\Events\WebhookReceived;
use Bhaidar\Checkeeper\Jobs\ProcessWebhookJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->all();
        $signature = $request->header('X-Checkeeper-Signature', '');
        $receivedAt = now()->toIso8601String();

        WebhookReceived::dispatch($payload, $signature, $receivedAt);

        if (config('checkeeper.queue.enabled')) {
            ProcessWebhookJob::dispatch($payload);
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }
}
