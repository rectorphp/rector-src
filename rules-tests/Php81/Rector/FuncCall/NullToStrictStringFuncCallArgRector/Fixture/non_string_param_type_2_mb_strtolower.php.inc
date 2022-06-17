<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrtolower
{
    public function run($string)
    {
        mb_strtolower($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrtolower
{
    public function run($string)
    {
        mb_strtolower((string) $string);
    }
}

?>
