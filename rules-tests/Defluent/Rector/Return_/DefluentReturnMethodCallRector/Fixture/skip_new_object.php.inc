<?php

namespace Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\Return_\DefluentReturnMethodCallRector\Source\SelfStaticButNewVersion;

final class SkipNewObject
{
    public function run(SelfStaticButNewVersion $selfButNewVersion)
    {
        return $selfButNewVersion->duplicate();
    }

    public function run2(SelfStaticButNewVersion $selfButNewVersion)
    {
        return $selfButNewVersion->duplicate2();
    }

    public function run3(SelfStaticButNewVersion $selfButNewVersion)
    {
        return $selfButNewVersion->duplicate3();
    }
}
