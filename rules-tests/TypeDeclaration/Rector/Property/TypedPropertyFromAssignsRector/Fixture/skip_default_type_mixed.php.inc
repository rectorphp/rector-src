<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class SkipDefaultTypeMixed
{
    private $property = true;

    public function run($prop)
    {
        $this->property = $prop;
    }
}
