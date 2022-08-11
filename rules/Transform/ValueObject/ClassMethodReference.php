<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use Rector\Core\Validation\RectorAssert;

final class ClassMethodReference
{
    public function __construct(
        private readonly string $class,
        private readonly string $method
    ) {
        RectorAssert::className($class);
        RectorAssert::methodName($method);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
