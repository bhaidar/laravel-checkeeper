<?php

namespace Bhaidar\Checkeeper\Client;

use Bhaidar\Checkeeper\Exceptions\CheckkeeperException;
use Illuminate\Http\Client\PendingRequest as LaravelPendingRequest;
use Illuminate\Http\Client\Response;

class PendingRequest
{
    public function __construct(
        protected LaravelPendingRequest $request
    ) {
    }

    public function get(string $url, ?array $query = null): Response
    {
        $response = $this->request->get($url, $query);

        return $this->handleResponse($response);
    }

    public function post(string $url, array $data = []): Response
    {
        $response = $this->request->post($url, $data);

        return $this->handleResponse($response);
    }

    public function put(string $url, array $data = []): Response
    {
        $response = $this->request->put($url, $data);

        return $this->handleResponse($response);
    }

    public function delete(string $url, array $data = []): Response
    {
        $response = $this->request->delete($url, $data);

        return $this->handleResponse($response);
    }

    protected function handleResponse(Response $response): Response
    {
        if ($response->failed()) {
            throw CheckkeeperException::fromResponse($response);
        }

        return $response;
    }
}
