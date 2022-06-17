<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvStrlen
{
    public function run($string)
    {
        iconv_strlen($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvStrlen
{
    public function run($string)
    {
        iconv_strlen((string) $string);
    }
}

?>
