<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use Closure;
use PhpParser\Node\Arg;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\Validation\RectorAssert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRectorTest
 */
final readonly class AddParamTypeForFunctionLikeWithinCallLikeArgDeclaration
{
    /**
     * @param int<0, max>|string $callLikePosition
     * @param int<0, max> $functionLikePosition
     * @param Type|Closure(Arg[], NodeTypeResolver): ?Type $paramType
     */
    public function __construct(
        private string $className,
        private string $methodName,
        private int|string $callLikePosition,
        private int $functionLikePosition,
        private Type|Closure $paramType
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

    /**
     * @return int<0, max>|string
     */
    public function getCallLikePosition(): int|string
    {
        return $this->callLikePosition;
    }

    /**
     * @return int<0, max>
     */
    public function getFunctionLikePosition(): int
    {
        return $this->functionLikePosition;
    }

    /**
     * @return Type|Closure(Arg[], NodeTypeResolver): ?Type
     */
    public function getParamType(): Type|Closure
    {
        return $this->paramType;
    }
}
