<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2BaseConvert
{
    public function run($num, $fromBase, $toBase)
    {
        base_convert($num, $fromBase, $toBase);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2BaseConvert
{
    public function run($num, $fromBase, $toBase)
    {
        base_convert((string) $num, $fromBase, $toBase);
    }
}

?>
