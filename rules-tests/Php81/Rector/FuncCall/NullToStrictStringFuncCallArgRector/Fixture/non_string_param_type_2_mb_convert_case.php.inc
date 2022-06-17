<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbConvertCase
{
    public function run($string, $mode)
    {
        mb_convert_case($string, $mode);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbConvertCase
{
    public function run($string, $mode)
    {
        mb_convert_case((string) $string, $mode);
    }
}

?>
