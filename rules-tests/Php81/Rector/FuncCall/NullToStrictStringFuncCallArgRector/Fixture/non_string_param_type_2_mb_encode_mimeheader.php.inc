<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEncodeMimeheader
{
    public function run($string)
    {
        mb_encode_mimeheader($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEncodeMimeheader
{
    public function run($string)
    {
        mb_encode_mimeheader((string) $string);
    }
}

?>
