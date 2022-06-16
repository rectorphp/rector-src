<?php declare(strict_types=1);

namespace Foo;

class Foo
{
    public function __construct()
    {
        $bar = 'baz';
        print $bar{2};
    }
}
