<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector\Fixture;

class SkipMixedConditionString
{
    private $name;

    public function __construct(mixed $name) {
        if ($name === null) {
            $name = 'default';
        }
        $this->name = $name;
    }
}
