<?php

namespace Bhaidar\Checkeeper\Http\Middleware;

use Bhaidar\Checkeeper\Exceptions\AuthenticationException;
use Bhaidar\Checkeeper\Support\WebhookSignatureValidator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('checkeeper.webhooks.enabled')) {
            return $next($request);
        }

        $signature = $request->header('X-Checkeeper-Signature');

        if (! $signature) {
            throw new AuthenticationException('Missing webhook signature', 401);
        }

        $secret = config('checkeeper.webhooks.secret');

        if (! $secret) {
            throw new AuthenticationException('Webhook secret not configured', 401);
        }

        $validator = new WebhookSignatureValidator($secret);

        if (! $validator->validate($request->getContent(), $signature)) {
            throw new AuthenticationException('Invalid webhook signature', 401);
        }

        return $next($request);
    }
}
