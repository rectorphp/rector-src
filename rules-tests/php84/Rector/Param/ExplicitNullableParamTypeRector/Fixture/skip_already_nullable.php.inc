<?php

namespace Rector\Tests\Php83\Rector\FuncCall\CombineHostPortLdapUriRector\Fixture;

class SkipAlreadyNullable
{
    public function run(?string $a = null)
    {
    }
}
