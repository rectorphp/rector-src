<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class MethodCallToStaticCall
{
    public function __construct(
        private string $oldClass,
        private string $oldMethod,
        private string $newClass,
        private string $newMethod
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
