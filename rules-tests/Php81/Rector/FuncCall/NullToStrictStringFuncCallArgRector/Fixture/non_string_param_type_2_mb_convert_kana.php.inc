<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbConvertKana
{
    public function run($string)
    {
        mb_convert_kana($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbConvertKana
{
    public function run($string)
    {
        mb_convert_kana((string) $string);
    }
}

?>
