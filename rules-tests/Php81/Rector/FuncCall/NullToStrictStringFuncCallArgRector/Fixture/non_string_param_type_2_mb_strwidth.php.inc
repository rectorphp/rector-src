<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrwidth
{
    public function run($string)
    {
        mb_strwidth($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrwidth
{
    public function run($string)
    {
        mb_strwidth((string) $string);
    }
}

?>
