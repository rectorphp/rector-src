<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumHex2bin
{
    public function run($string, $ignore)
    {
        sodium_hex2bin($string, $ignore);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumHex2bin
{
    public function run($string, $ignore)
    {
        sodium_hex2bin((string) $string, (string) $ignore);
    }
}

?>
