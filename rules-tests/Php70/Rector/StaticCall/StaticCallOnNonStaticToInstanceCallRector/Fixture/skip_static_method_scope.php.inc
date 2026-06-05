<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SkipStaticMethodScope
{
    public function create(array $data)
    {
        return $this;
    }

    public static function make(array $data)
    {
        return SkipStaticMethodScope::create($data);
    }
}
