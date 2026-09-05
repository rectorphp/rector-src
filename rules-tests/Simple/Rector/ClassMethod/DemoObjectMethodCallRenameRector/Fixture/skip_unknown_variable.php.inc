<?php

namespace Rector\Tests\Simple\Rector\ClassMethod\DemoObjectMethodCallRenameRector\Fixture;

class SkipUnknownVariable
{
    public function run($dateTime)
    {
        $dateTime->oldMethod();
    }
}
