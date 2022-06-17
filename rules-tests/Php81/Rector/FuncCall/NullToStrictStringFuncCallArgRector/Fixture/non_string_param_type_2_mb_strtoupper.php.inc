<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrtoupper
{
    public function run($string)
    {
        mb_strtoupper($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrtoupper
{
    public function run($string)
    {
        mb_strtoupper((string) $string);
    }
}

?>
