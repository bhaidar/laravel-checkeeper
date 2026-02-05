<?php

namespace Bhaidar\Checkeeper\Exceptions;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class AuthenticationException extends CheckkeeperException implements Responsable
{
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            ['message' => $this->getMessage()],
            $this->statusCode ?: 401
        );
    }
}
