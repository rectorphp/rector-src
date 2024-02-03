<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

use stdClass;

final class SkipWriteInTraitProperty
{
    use WritableTrait;
    
    public function __construct(private stdClass $myProperty)
    {
    }
}

trait WritableTrait
{
    public function setProperty(stdClass $myProperty): void
    {
        $this->myProperty = $myProperty;
    }
}
