<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrcut
{
    public function run($string)
    {
        mb_strcut($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrcut
{
    public function run($string)
    {
        mb_strcut((string) $string);
    }
}

?>
