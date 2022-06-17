<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcadd
{
    public function run($num1, $num2)
    {
        bcadd($num1, $num2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcadd
{
    public function run($num1, $num2)
    {
        bcadd((string) $num1, (string) $num2);
    }
}

?>
