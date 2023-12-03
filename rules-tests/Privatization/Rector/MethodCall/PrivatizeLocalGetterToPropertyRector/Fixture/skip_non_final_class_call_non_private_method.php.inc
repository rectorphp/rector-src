<?php

namespace Rector\Tests\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector\Fixture;

class SkipNonFinalClassCallNonPrivateMethod
{
    private $some;

    public function run()
    {
        return $this->getSome() + 5;
    }

    // public can be overridden by child
    public function getSome()
    {
        return $this->some;
    }
}
