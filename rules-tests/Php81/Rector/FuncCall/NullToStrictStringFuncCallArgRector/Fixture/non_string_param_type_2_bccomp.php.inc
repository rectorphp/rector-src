<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bccomp
{
    public function run($num1, $num2)
    {
        bccomp($num1, $num2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bccomp
{
    public function run($num1, $num2)
    {
        bccomp((string) $num1, (string) $num2);
    }
}

?>
