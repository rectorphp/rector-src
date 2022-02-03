<?php

namespace Rector\Tests\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector\Fixture;

use Rector\Tests\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector\Source\ObjectWithMagicCallsMultiParam;

final class SkipNoMethodOnMultiParam
{
    public function run(ObjectWithMagicCallsMultiParam $obj)
    {
        $obj->name = 20;
    }
}
