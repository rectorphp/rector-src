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

    // exact match, or native parent/interface check for autoloadable classes
    public function isInstanceOf(string ...$classNames): bool
    {
        foreach ($classNames as $className) {
            if ($this->className === $className) {
                return true;
            }

            if (is_a($this->className, $className, true)) {
                return true;
            }
        }

        return false;
    }
}
