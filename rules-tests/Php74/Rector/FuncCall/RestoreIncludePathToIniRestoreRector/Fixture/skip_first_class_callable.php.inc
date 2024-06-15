<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\Fixture;

final class SkipFirstClassCallable
{
    public function run()
    {
        return restore_include_path(...);
    }
}

?>