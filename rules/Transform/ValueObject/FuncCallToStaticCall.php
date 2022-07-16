<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use Rector\Core\Validation\RectorAssert;

final class FuncCallToStaticCall
{
    public function __construct(
        private readonly string $oldFuncName,
        private readonly string $newClassName,
        private readonly string $newMethodName
    ) {
        RectorAssert::functionName($oldFuncName);

        RectorAssert::className($newClassName);
        RectorAssert::methodName($newMethodName);
    }

    public function getOldFuncName(): string
    {
        return $this->oldFuncName;
    }

    public function getNewClassName(): string
    {
        return $this->newClassName;
    }

    public function getNewMethodName(): string
    {
        return $this->newMethodName;
    }
}
