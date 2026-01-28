<?php

namespace Bhaidar\Checkeeper\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $payload
    ) {
        $this->onQueue(config('checkeeper.queue.queue'));
        $this->onConnection(config('checkeeper.queue.connection'));
    }

    public function handle(): void
    {
        // Process webhook payload
        // Dispatch domain-specific events based on webhook type
        // Example: if ($this->payload['event'] === 'check.delivered') { ... }
    }
}
