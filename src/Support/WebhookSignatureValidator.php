<?php

namespace Bhaidar\Checkeeper\Support;

class WebhookSignatureValidator
{
    public function __construct(
        protected string $secret
    ) {
    }

    public function validate(string $payload, string $signature): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $this->secret);

        return hash_equals($expectedSignature, $signature);
    }
}
