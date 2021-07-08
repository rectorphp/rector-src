<?php

namespace Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Source\SelfButCloneVersion;

final class SkipCloneObject
{
    public function run(SelfButCloneVersion $selfButCloneVersion)
    {
        return $selfButCloneVersion->withStatus(500);
    }
}
