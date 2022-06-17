<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcdiv
{
    public function run($num1, $num2)
    {
        bcdiv($num1, $num2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcdiv
{
    public function run($num1, $num2)
    {
        bcdiv((string) $num1, (string) $num2);
    }
}

?>
