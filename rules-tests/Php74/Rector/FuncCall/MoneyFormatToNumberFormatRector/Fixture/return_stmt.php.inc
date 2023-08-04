<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\Fixture;

final class ReturnStmt
{
    public function run($value)
    {
        return money_format('%i', $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\Fixture;

final class ReturnStmt
{
    public function run($value)
    {
        return number_format(round($value, 2, PHP_ROUND_HALF_ODD), 2, '.', '');
    }
}

?>
