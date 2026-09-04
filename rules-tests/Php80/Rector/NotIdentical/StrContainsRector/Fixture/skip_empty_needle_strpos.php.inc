<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipEmptyNeedleStrpos
{
    public function run()
    {
        return strpos('abc', '') !== false;
    }
}
