<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Validation\RectorAssert;

final readonly class AddParamTypeDeclaration
{
    /**
     * @param int<0, max> $position
     */
    public function __construct(
        private string $className,
        private string $methodName,
        private int $position,
        private Type $paramType
    ) {
        RectorAssert::className($className);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->className);
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getParamType(): Type
    {
        return $this->paramType;
    }
}
