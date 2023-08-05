<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

abstract class SkipPublicMethodWithDefaultValue
{
    public function toJson()
    {
        return $this->toArray(true);
    }

    public function __toString()
    {
        return implode(', ', $this->toArray());
    }

    final public function toArray($cols = false)
    {
        if ((! is_bool($cols)) && (! is_array($cols))) {
            throw new Exception('Invalid value cols');
        }
    }

}
