<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType
{
    public function run($subject)
    {
        preg_split('#a#', $subject);
    }
}

?>
