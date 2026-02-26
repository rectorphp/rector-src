<?php

namespace Rector\Tests\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector\Fixture;

final class ExceptionWithCustomParam extends \RuntimeException
{
    public function __construct(
        public readonly bool $flag,
        string $message,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function run(): void
    {
        try {
            throw new \RuntimeException('root');
        } catch (\Throwable $e) {
            throw new ExceptionWithCustomParam(flag: true, message: $e->getMessage(), previous: $e);
        }
    }
}
