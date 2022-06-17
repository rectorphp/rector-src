<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbDecodeMimeheader
{
    public function run($string)
    {
        mb_decode_mimeheader($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbDecodeMimeheader
{
    public function run($string)
    {
        mb_decode_mimeheader((string) $string);
    }
}

?>
