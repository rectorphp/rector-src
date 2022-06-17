<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbDecodeNumericentity
{
    public function run($string, $map)
    {
        mb_decode_numericentity($string, $map);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbDecodeNumericentity
{
    public function run($string, $map)
    {
        mb_decode_numericentity((string) $string, $map);
    }
}

?>
