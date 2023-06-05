<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipMagicallyAssignedFetch
{
    private $seemsUnused;

    private $anotherName = 'asdf';

    public function getValue()
    {
        // but it might be used :)
        $this->{$this->anotherName} = 'foo';
    }
}
