<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\Fixture;

final class SkipOtherFuncCall
{
    public function run($value)
    {
        return strlen($value);
    }
}

?>