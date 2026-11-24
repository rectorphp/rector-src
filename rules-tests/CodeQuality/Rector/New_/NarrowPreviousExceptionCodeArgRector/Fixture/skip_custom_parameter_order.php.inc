<?php

namespace Rector\Tests\CodeQuality\Rector\New_\NarrowPreviousExceptionCodeArgRector\Fixture;

final class CustomOrderException extends \RuntimeException
{
    public function __construct(string $message, \Throwable $previous, int $code)
    {
        parent::__construct($message, $code, $previous);
    }
}

final class SkipCustomParameterOrder
{
    public function run()
    {
        try {
            $this->run();
        } catch (\Throwable $throwable) {
            throw new CustomOrderException('Failed', $throwable->getCode(), $throwable);
        }
    }
}
