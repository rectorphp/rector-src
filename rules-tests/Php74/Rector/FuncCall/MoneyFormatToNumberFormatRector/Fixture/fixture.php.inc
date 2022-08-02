<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = money_format('%i', $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $roundedValue = round($value, 2, PHP_ROUND_HALF_ODD);
        $value = number_format($roundedValue, 2, '.', '');
    }
}

?>
