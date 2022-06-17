<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrShuffle
{
    public function run($string)
    {
        str_shuffle($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrShuffle
{
    public function run($string)
    {
        str_shuffle((string) $string);
    }
}

?>
