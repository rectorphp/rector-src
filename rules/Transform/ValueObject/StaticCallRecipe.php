<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use Rector\Core\Validation\RectorAssert;

final class StaticCallRecipe
{
    public function __construct(
        private readonly string $className,
        private readonly string $methodName,
    ) {
        RectorAssert::className($className);
        RectorAssert::methodName($methodName);
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }
}
