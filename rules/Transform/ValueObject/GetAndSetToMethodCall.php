<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class GetAndSetToMethodCall
{
    /**
     * @param class-string $classType
     */
    public function __construct(
        private readonly string $classType,
        private readonly string $getMethod,
        private readonly string $setMethod
    ) {
        RectorAssert::methodName($getMethod);
        RectorAssert::methodName($setMethod);
    }

    public function getGetMethod(): string
    {
        return $this->getMethod;
    }

    public function getSetMethod(): string
    {
        return $this->setMethod;
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->classType);
    }
}
