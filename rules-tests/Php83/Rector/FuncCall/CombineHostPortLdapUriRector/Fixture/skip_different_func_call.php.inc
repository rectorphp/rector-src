<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class SkipDifferentFuncCall
{
    public function run($a, $b)
    {
        return str_contains($a, $b);
    }
}
