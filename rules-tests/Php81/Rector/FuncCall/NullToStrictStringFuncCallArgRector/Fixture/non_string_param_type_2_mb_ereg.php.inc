<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEreg
{
    public function run($pattern, $string)
    {
        mb_ereg($pattern, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEreg
{
    public function run($pattern, $string)
    {
        mb_ereg((string) $pattern, (string) $string);
    }
}

?>
