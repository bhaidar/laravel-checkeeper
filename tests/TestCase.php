<?php

namespace Bhaidar\Checkeeper\Tests;

use Bhaidar\Checkeeper\CheckkeeperServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            CheckkeeperServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('checkeeper.api_key', 'test-api-key');
        config()->set('checkeeper.base_url', 'https://api.checkeeper.com/v3');
        config()->set('checkeeper.timeout', 30);
        config()->set('checkeeper.retry', ['times' => 3, 'sleep' => 100]);
        config()->set('checkeeper.webhooks.enabled', true);
        config()->set('checkeeper.webhooks.secret', 'test-webhook-secret');
        config()->set('checkeeper.webhooks.route', 'checkeeper/webhook');
        config()->set('checkeeper.webhooks.middleware', ['api']);
        config()->set('checkeeper.queue.enabled', true);
        config()->set('checkeeper.queue.connection', 'sync');
        config()->set('checkeeper.queue.queue', 'checkeeper');
    }
}
