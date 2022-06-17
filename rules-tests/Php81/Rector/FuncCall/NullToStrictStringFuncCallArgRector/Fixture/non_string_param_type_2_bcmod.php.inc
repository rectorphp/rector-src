<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcmod
{
    public function run($num1, $num2)
    {
        bcmod($num1, $num2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcmod
{
    public function run($num1, $num2)
    {
        bcmod((string) $num1, (string) $num2);
    }
}

?>
