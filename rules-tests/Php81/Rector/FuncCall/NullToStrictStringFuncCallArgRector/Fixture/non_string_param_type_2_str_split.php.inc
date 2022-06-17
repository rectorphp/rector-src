<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrSplit
{
    public function run($string)
    {
        str_split($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrSplit
{
    public function run($string)
    {
        str_split((string) $string);
    }
}

?>
