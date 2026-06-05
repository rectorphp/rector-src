<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SkipStaticClosureScope
{
    public function where(string $column, $value)
    {
        return $this;
    }

    public static function boot(): callable
    {
        return static function () {
            return SkipStaticClosureScope::where('active', true);
        };
    }
}
