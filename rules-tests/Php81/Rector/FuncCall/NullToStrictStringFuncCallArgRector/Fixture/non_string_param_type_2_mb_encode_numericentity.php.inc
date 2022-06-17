<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEncodeNumericentity
{
    public function run($string, $map)
    {
        mb_encode_numericentity($string, $map);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEncodeNumericentity
{
    public function run($string, $map)
    {
        mb_encode_numericentity((string) $string, $map);
    }
}

?>
