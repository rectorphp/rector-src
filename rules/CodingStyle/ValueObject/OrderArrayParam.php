<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class OrderArrayParam
{
    public function __construct(
        private readonly string $className,
    )
    {
        RectorAssert::className($className);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->className);
    }
}
