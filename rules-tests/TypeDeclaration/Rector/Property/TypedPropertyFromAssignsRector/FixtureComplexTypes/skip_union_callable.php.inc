<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixtureComplexTypes;

class SkipUnionCallable
{
    private $property;

    public function run(callable $prop): void
    {
        if (rand(0, 1)) {
            $this->property = $prop;
        } else {
            $this->property = false;
        }
    }
}
