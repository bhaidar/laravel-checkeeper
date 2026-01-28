<?php

use Bhaidar\Checkeeper\Http\Controllers\WebhookController;
use Bhaidar\Checkeeper\Http\Middleware\VerifyWebhookSignature;
use Illuminate\Support\Facades\Route;

Route::post(config('checkeeper.webhooks.route'), WebhookController::class)
    ->middleware(config('checkeeper.webhooks.middleware'))
    ->middleware(VerifyWebhookSignature::class)
    ->name('checkeeper.webhook');
