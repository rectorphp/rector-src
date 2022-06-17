<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Utf8Encode
{
    public function run($string)
    {
        utf8_encode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Utf8Encode
{
    public function run($string)
    {
        utf8_encode((string) $string);
    }
}

?>
