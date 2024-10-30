<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetParentClassNoArgsRector\Fixture;

class SkipDifferentFuncCall
{
    public function run($a, $b)
    {
        return str_contains($a, $b);
    }
}
