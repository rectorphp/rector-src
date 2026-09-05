<?php

namespace Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\Fixture;

class SkipOtherClass
{
    public function run()
    {
        $value = new \stdClass();
        $value->oldMethod();
    }
}
