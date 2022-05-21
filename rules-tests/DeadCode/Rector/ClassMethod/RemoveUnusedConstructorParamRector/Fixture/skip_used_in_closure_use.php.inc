<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class SomeClass
{
    public function __construct($hey, $man)
    {
        echo $hey . ' ';

        (function () use ($man) {
            echo $man;
        })();
    }
}