<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class SkipWithConstructor
{
    private $required;

    public function __construct($required, $value = [])
    {
        $this->required = $required;
    }

    public function doWork()
    {
        $this->required = 5;
        return 5;
    }
}

class TryWithConstructor
{
    public function run()
    {
        return SkipWithConstructor::doWork();
    }
}
