<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Validation\RectorAssert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRectorTest
 */
final readonly class AddParamTypeForFunctionLikeWithinCallLikeParamDeclaration
{
    /**
     * @param int<0, max> $callLikePosition
     * @param int<0, max> $functionLikePosition
     */
    public function __construct(
        private string $className,
        private string $methodName,
        private int $callLikePosition,
        private int $functionLikePosition,
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

    public function getMethodCallPosition(): int
    {
        return $this->callLikePosition;
    }

    public function getFunctionLikePosition(): int
    {
        return $this->functionLikePosition;
    }

    public function getParamType(): Type
    {
        return $this->paramType;
    }
}
