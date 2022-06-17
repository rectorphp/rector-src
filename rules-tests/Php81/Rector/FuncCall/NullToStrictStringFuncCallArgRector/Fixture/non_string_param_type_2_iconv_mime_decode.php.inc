<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvMimeDecode
{
    public function run($string)
    {
        iconv_mime_decode($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvMimeDecode
{
    public function run($string)
    {
        iconv_mime_decode((string) $string);
    }
}

?>
