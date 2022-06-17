<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSplit
{
    public function run($pattern, $string)
    {
        mb_split($pattern, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSplit
{
    public function run($pattern, $string)
    {
        mb_split((string) $pattern, (string) $string);
    }
}

?>
