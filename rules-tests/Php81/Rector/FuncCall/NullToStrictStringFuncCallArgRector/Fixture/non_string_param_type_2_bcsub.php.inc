<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcsub
{
    public function run($num1, $num2)
    {
        bcsub($num1, $num2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcsub
{
    public function run($num1, $num2)
    {
        bcsub((string) $num1, (string) $num2);
    }
}

?>
