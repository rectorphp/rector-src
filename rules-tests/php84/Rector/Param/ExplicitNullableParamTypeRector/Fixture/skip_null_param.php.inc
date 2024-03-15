<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class SkipNullParam
{
    public function run(null $a = null)
    {
    }
}
