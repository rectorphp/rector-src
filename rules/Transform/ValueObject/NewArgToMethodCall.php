<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Core\Validation\RectorAssert;

final class NewArgToMethodCall
{
    public function __construct(
        private readonly string $type,
        private readonly mixed $value,
        private readonly string $methodCall
    ) {
        RectorAssert::className($type);
        RectorAssert::className($methodCall);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->type);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getMethodCall(): string
    {
        return $this->methodCall;
    }
}
