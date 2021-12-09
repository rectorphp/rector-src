<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class SingleToManyMethod
{
    public function __construct(
        private readonly string $class,
        private readonly string $singleMethodName,
        private readonly string $manyMethodName
    ) {
        RectorAssert::className($class);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->class);
    }

    public function getSingleMethodName(): string
    {
        return $this->singleMethodName;
    }

    public function getManyMethodName(): string
    {
        return $this->manyMethodName;
    }
}
