<?php

namespace Foo7;

class Foo
{
    public function __construct()
    {
        $bar = 'baz';
        print $bar{2};
    }
}
