<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrRepeat
{
    public function run($string, $times)
    {
        str_repeat($string, $times);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrRepeat
{
    public function run($string, $times)
    {
        str_repeat((string) $string, $times);
    }
}

?>
