<?php

namespace Bhaidar\Checkeeper\Events;

use Bhaidar\Checkeeper\DataTransferObjects\CheckStatusData;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public CheckStatusData $check,
        public array $metadata = []
    ) {
    }
}
