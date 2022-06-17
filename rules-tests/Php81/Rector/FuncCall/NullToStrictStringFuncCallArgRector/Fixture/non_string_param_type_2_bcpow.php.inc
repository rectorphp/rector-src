<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcpow
{
    public function run($num1, $exponent)
    {
        bcpow($num1, $exponent);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcpow
{
    public function run($num1, $exponent)
    {
        bcpow((string) $num1, (string) $exponent);
    }
}

?>
