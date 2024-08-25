<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\AddClosureParamTypeFromArgRectorTest
 */
final readonly class AddClosureParamTypeFromArg
{
    /**
     * @param int<0, max>|string $callLikePosition
     * @param int<0, max> $functionLikePosition
     * @param int<0, max>|string $fromArgPosition
     */
    public function __construct(
        private string $className,
        private string $methodName,
        private int|string $callLikePosition,
        private int $functionLikePosition,
        private int|string $fromArgPosition,
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
     * @return int<0, max>|string
     */
    public function getFromArgPosition(): int|string
    {
        return $this->fromArgPosition;
    }
}
