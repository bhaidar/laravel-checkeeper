<?php

namespace Bhaidar\Checkeeper\Resources;

use Bhaidar\Checkeeper\Client\PendingRequest;
use Illuminate\Support\Collection;

class TemplateResource
{
    public function __construct(
        protected PendingRequest $request
    ) {
    }

    public function list(): Collection
    {
        $response = $this->request->get('/templates');

        return collect($response->json('data', []));
    }
}
