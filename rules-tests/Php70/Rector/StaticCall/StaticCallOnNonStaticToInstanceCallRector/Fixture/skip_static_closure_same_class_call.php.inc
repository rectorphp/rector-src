<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SkipStaticClosureSameClassCall
{
    public function boot(): void
    {
        // static closure — no $this, must not rewrite
        $callback = static function (): void {
            SkipStaticClosureSameClassCall::doWork();
        };
    }

    public function doWork(): void
    {
    }
}
