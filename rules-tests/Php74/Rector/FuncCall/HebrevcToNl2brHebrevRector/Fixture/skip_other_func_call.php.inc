<?php

namespace Rector\Tests\Php74\Rector\FuncCall\HebrevcToNl2brHebrevRector\Fixture;

final class SkipOtherFuncCall
{
    public function run($value)
    {
        return strlen($value);
    }
}

?>