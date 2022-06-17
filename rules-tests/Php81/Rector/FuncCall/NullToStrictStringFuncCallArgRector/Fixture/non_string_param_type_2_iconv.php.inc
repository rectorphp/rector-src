<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Iconv
{
    public function run($from_encoding, $to_encoding, $string)
    {
        iconv($from_encoding, $to_encoding, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Iconv
{
    public function run($from_encoding, $to_encoding, $string)
    {
        iconv((string) $from_encoding, (string) $to_encoding, (string) $string);
    }
}

?>
