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

    // exact class match; SimpleScope has no reflection, so no parent/interface check
    public function isInstanceOf(string ...$classNames): bool
    {
        return in_array($this->className, $classNames, true);
    }
}
