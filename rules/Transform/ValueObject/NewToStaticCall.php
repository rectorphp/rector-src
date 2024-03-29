<?php

declare(strict_types=1);

namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
use Rector\Validation\RectorAssert;

final readonly class NewToStaticCall
{
    public function __construct(
        private string $type,
        private string $staticCallClass,
        private string $staticCallMethod
    ) {
        RectorAssert::className($type);

        RectorAssert::className($staticCallClass);
        RectorAssert::methodName($staticCallMethod);
    }

    public function getObjectType(): ObjectType
    {
        return new ObjectType($this->type);
    }

    public function getStaticCallClass(): string
    {
        return $this->staticCallClass;
    }

    public function getStaticCallMethod(): string
    {
        return $this->staticCallMethod;
    }
}
