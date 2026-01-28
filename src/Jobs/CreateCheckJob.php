<?php

namespace Bhaidar\Checkeeper\Jobs;

use Bhaidar\Checkeeper\DataTransferObjects\CheckData;
use Bhaidar\Checkeeper\Events\CheckCreated;
use Bhaidar\Checkeeper\Facades\Checkeeper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCheckJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        public CheckData $checkData,
        public ?string $callbackUrl = null
    ) {
        $this->onQueue(config('checkeeper.queue.queue'));
        $this->onConnection(config('checkeeper.queue.connection'));
    }

    public function handle(): void
    {
        $result = Checkeeper::checks()->create($this->checkData);

        CheckCreated::dispatch($result, [
            'callback_url' => $this->callbackUrl,
        ]);
    }
}
