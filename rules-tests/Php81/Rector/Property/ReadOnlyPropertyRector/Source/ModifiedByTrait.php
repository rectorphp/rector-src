<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Source;

trait ModifiedByTrait
{
    public function modify()
    {
        $this->name = 'modified';
    }
}
