<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class NewToMethodCall
{
    public function __construct(
        private readonly string $newType,
        private readonly string $serviceType,
        private readonly string $serviceMethod
    ) {
        RectorAssert::className($newType);
        RectorAssert::className($serviceType);
        RectorAssert::methodName($serviceMethod);
    }

    public function getNewObjectType(): ObjectType
    {
        return new ObjectType($this->newType);
    }

    public function getServiceObjectType(): ObjectType
    {
        return new ObjectType($this->serviceType);
    }

    public function getServiceMethod(): string
    {
        return $this->serviceMethod;
    }
}
