<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class Grandpa
{
    protected function test()
    {
    }
}

class Father extends Grandpa
{
    protected function test()
    {
        parent::test();
    }
}

class Son extends Father
{
    protected function test()
    {
        Grandpa::test();
    }
}
