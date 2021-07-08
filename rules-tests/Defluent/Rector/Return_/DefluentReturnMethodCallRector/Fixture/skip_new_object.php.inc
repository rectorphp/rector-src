<?php

namespace Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Source\SelfStaticButNewVersion;

final class SkipNewObject
{
    public function run(SelfStaticButNewVersion $selfButNewVersion)
    {
        return $selfButNewVersion->duplicateSelf();
    }

    public function run2(SelfStaticButNewVersion $selfButNewVersion)
    {
        return $selfButNewVersion->duplicateStatic();
    }

    public function run3(SelfStaticButNewVersion $selfButNewVersion)
    {
        return $selfButNewVersion->duplicateThis();
    }
}
