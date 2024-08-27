<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class AddClosureParamTypeFromArg
{
    /**
     * @param int<0, max> $callLikePosition
     * @param int<0, max> $functionLikePosition
     * @param int<0, max> $fromArgPosition
     */
    public function __construct(
        private string $className,
        private string $methodName,
        private int $callLikePosition,
        private int $functionLikePosition,
        private int $fromArgPosition,
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
     * @return int<0, max>
     */
    public function getCallLikePosition(): int
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
     * @return int<0, max>
     */
    public function getFromArgPosition(): int
    {
        return $this->fromArgPosition;
    }
}
