<?php

declare(strict_types=1);

namespace Rector\Renaming\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class RenameFunctionLikeParamWithinCallLikeParam
{
    /**
     * @param int<0, max>|string $callLikePosition
     * @param int<0, max> $functionLikePosition
     */
    public function __construct(
        private string $className,
        private string $methodName,
        private int|string $callLikePosition,
        private int $functionLikePosition,
        private string $newParamName
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

    public function getNewParamName(): string
    {
        return $this->newParamName;
    }
}
