<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrPad
{
    public function run($string, $length)
    {
        str_pad($string, $length);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrPad
{
    public function run($string, $length)
    {
        str_pad((string) $string, $length);
    }
}

?>
