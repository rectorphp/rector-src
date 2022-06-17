<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbDetectEncoding
{
    public function run($string)
    {
        mb_detect_encoding($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbDetectEncoding
{
    public function run($string)
    {
        mb_detect_encoding((string) $string);
    }
}

?>
