<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

use Rector\Core\Validation\RectorAssert;

final class RemoveParentMethodCall
{
    public function __construct(private readonly string $parentClass, private readonly string $methodName)
    {
        RectorAssert::className($parentClass);
    }

    public function getParentClass(): string
    {
        return $this->parentClass;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}

