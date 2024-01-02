<?php

declare(strict_types=1);

namespace Rector\Visibility\ValueObject;

use Rector\Validation\RectorAssert;

final readonly class ChangeMethodVisibility
{
    public function __construct(
        private string $class,
        private string $method,
        private int $visibility
    ) {
        RectorAssert::className($class);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getVisibility(): int
    {
        return $this->visibility;
    }
}
