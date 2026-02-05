<?php

namespace Bhaidar\Checkeeper\Client;

use Bhaidar\Checkeeper\Exceptions\CheckkeeperException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest as LaravelPendingRequest;
use Illuminate\Http\Client\Response;

class PendingRequest
{
    public function __construct(
        protected LaravelPendingRequest $request
    ) {
    }

    /**
     * @throws CheckkeeperException
     * @throws ConnectionException
     */
    public function get(string $url, ?array $query = null): Response
    {
        $response = $this->request->get($url, $query);

        return $this->handleResponse($response);
    }

    /**
     * @throws CheckkeeperException
     * @throws ConnectionException
     */
    public function post(string $url, array $data = []): Response
    {
        $response = $this->request->post($url, $data);

        return $this->handleResponse($response);
    }

    /**
     * @throws CheckkeeperException
     * @throws ConnectionException
     */
    public function put(string $url, array $data = []): Response
    {
        $response = $this->request->put($url, $data);

        return $this->handleResponse($response);
    }

    /**
     * @throws CheckkeeperException
     * @throws ConnectionException
     */
    public function delete(string $url, array $data = []): Response
    {
        $response = $this->request->delete($url, $data);

        return $this->handleResponse($response);
    }

    /**
     * @throws CheckkeeperException
     */
    protected function handleResponse(Response $response): Response
    {
        if ($response->failed()) {
            throw CheckkeeperException::fromResponse($response);
        }

        return $response;
    }
}
