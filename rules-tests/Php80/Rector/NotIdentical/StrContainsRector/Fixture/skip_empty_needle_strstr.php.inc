<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipEmptyNeedleStrstr
{
    public function run()
    {
        return strstr('abc', '') !== false;
    }
}
