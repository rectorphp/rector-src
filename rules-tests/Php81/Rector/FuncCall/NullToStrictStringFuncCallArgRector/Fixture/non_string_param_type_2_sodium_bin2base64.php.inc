<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumBin2base64
{
    public function run($string, $id)
    {
        sodium_bin2base64($string, $id);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumBin2base64
{
    public function run($string, $id)
    {
        sodium_bin2base64((string) $string, $id);
    }
}

?>
