<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class MethodCallToStaticCall
{
    public function __construct(
        private readonly string $oldClass,
        private readonly string $oldMethod,
        private readonly string $newClass,
        private readonly string $newMethod
    ) {
        RectorAssert::className($oldClass);
        RectorAssert::className($oldMethod);

        RectorAssert::className($newClass);
        RectorAssert::className($newMethod);
    }

    public function getOldObjectType(): ObjectType
    {
        return new ObjectType($this->oldClass);
    }

    public function getOldMethod(): string
    {
        return $this->oldMethod;
    }

    public function getNewClass(): string
    {
        return $this->newClass;
    }

    public function getNewMethod(): string
    {
        return $this->newMethod;
    }
}
