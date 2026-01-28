<?php

namespace Bhaidar\Checkeeper\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class CheckkeeperException extends Exception
{
    public function __construct(
        string $message = '',
        public readonly int $statusCode = 0,
        public readonly array $errors = [],
        ?Exception $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        $message = $data['message'] ?? 'An error occurred';
        $errors = $data['errors'] ?? [];

        return match ($response->status()) {
            401 => new AuthenticationException($message, 401, $errors),
            403 => new AuthenticationException($message, 403, $errors),
            404 => new NotFoundException($message, 404, $errors),
            422 => new ValidationException($message, 422, $errors),
            default => new self($message, $response->status(), $errors),
        };
    }
}
