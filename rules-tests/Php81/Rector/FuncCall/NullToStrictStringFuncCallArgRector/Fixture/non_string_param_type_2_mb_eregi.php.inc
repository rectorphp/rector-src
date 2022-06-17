<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregi
{
    public function run($pattern, $string)
    {
        mb_eregi($pattern, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregi
{
    public function run($pattern, $string)
    {
        mb_eregi((string) $pattern, (string) $string);
    }
}

?>
