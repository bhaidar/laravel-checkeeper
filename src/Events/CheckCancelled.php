<?php

namespace Bhaidar\Checkeeper\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $checkId
    ) {
    }
}
