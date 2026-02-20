<?php

/**
 * No Namespace and multiple classes is on purpose to reproduce the issue
 */
class HttpException extends \RuntimeException
{
    private int $statusCode;
    private array $headers;

    public function __construct(int $statusCode, string $message = '', ?\Throwable $previous = null, array $headers = [], int $code = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;

        parent::__construct($message, $code, $previous);
    }
}

class BadGatewayHttpException extends HttpException
{
    public function __construct(string $message = '', ?Throwable $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct(Response::HTTP_BAD_GATEWAY, $message, $previous, $headers, $code);
    }
}


try {
    // do sth
} catch (\Throwable $exception) {
	throw new BadGatewayHttpException('test exception', $exception);
}
