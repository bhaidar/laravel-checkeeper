<?php

namespace Bhaidar\Checkeeper\Resources;

use Bhaidar\Checkeeper\Client\PendingRequest;

class TeamResource
{
    public function __construct(
        protected PendingRequest $request
    ) {
    }

    public function info(): array
    {
        $response = $this->request->get('/team/info');

        return $response->json('data', []);
    }
}
