<?php

namespace Rector\Tests\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector\Fixture;

class SkipFirstClassCallable
{
    private $some;

    public function run()
    {
        return $this->some(...);
    }

    private function some()
    {
        return $this->some;
    }
}
