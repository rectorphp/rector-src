<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcmul
{
    public function run($num1, $num2)
    {
        bcmul($num1, $num2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcmul
{
    public function run($num1, $num2)
    {
        bcmul((string) $num1, (string) $num2);
    }
}

?>
