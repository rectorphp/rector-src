<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Utf8Decode
{
    public function run($string)
    {
        utf8_decode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Utf8Decode
{
    public function run($string)
    {
        utf8_decode((string) $string);
    }
}

?>
