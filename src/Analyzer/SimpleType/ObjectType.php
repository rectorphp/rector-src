<?php

declare(strict_types=1);

namespace Rector\Analyzer\SimpleType;

use Rector\Analyzer\SimpleType\Contract\SimpleTypeInterface;

final readonly class ObjectType implements SimpleTypeInterface
{
    public function __construct(
        private string $className
    ) {
    }

    public function describe(): string
    {
        return $this->className;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}
