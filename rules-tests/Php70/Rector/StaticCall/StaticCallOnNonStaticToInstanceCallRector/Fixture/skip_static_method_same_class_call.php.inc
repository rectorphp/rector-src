<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SkipStaticMethodSameClassCall
{
    public static function run(): string
    {
        // enclosing function is static — no $this, must not rewrite
        return SkipStaticMethodSameClassCall::doWork();
    }

    public function doWork(): string
    {
        return 'work done';
    }
}
