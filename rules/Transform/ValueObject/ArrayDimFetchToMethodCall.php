<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

class ArrayDimFetchToMethodCall
{
    public function __construct(
        private readonly string $class,
        private readonly string $method
    ) {
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
