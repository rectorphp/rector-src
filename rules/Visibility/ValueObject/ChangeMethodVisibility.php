<?php

declare(strict_types=1);

namespace Rector\Visibility\ValueObject;

use Rector\Validation\RectorAssert;
use Rector\ValueObject\Visibility;
use Webmozart\Assert\Assert;

final readonly class ChangeMethodVisibility
{
    /**
     * @param Visibility::* $visibility
     */
    public function __construct(
        private string $class,
        private string $method,
        private int $visibility
    ) {
        RectorAssert::className($class);

        Assert::oneOf($visibility, [
            Visibility::PUBLIC,
            Visibility::PROTECTED,
            Visibility::PRIVATE,
            Visibility::STATIC,
            Visibility::ABSTRACT,
            Visibility::FINAL,
        ]);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return Visibility::*
     */
    public function getVisibility(): int
    {
        return $this->visibility;
    }
}
