<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumBin2hex
{
    public function run($string)
    {
        sodium_bin2hex($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumBin2hex
{
    public function run($string)
    {
        sodium_bin2hex((string) $string);
    }
}

?>
