<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Hex2bin
{
    public function run($string)
    {
        hex2bin($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Hex2bin
{
    public function run($string)
    {
        hex2bin((string) $string);
    }
}

?>
