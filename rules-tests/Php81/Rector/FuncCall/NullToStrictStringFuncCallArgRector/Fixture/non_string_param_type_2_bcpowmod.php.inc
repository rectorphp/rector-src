<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcpowmod
{
    public function run($num1, $exponent, $modulus)
    {
        bcpowmod($num1, $exponent, $modulus);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Bcpowmod
{
    public function run($num1, $exponent, $modulus)
    {
        bcpowmod((string) $num1, (string) $exponent, (string) $modulus);
    }
}

?>
