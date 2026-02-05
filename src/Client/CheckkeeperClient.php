<?php

namespace Bhaidar\Checkeeper\Client;

use Bhaidar\Checkeeper\Resources\CheckResource;
use Bhaidar\Checkeeper\Resources\TeamResource;
use Bhaidar\Checkeeper\Resources\TemplateResource;
use Illuminate\Support\Facades\Http;

class CheckkeeperClient
{
    protected PendingRequest $request;

    public function __construct(
        protected string $apiKey,
        protected string $baseUrl,
        protected int $timeout,
        protected array $retry
    ) {
        $this->request = $this->buildRequest();
    }

    protected function buildRequest(): PendingRequest
    {
        $laravelRequest = Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->timeout($this->timeout)
            ->acceptJson();

        return new PendingRequest($laravelRequest);
    }

    public function checks(): CheckResource
    {
        return new CheckResource($this->request);
    }

    public function team(): TeamResource
    {
        return new TeamResource($this->request);
    }

    public function templates(): TemplateResource
    {
        return new TemplateResource($this->request);
    }
}
