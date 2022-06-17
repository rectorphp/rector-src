<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvStrrpos
{
    public function run($haystack, $needle)
    {
        iconv_strrpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvStrrpos
{
    public function run($haystack, $needle)
    {
        iconv_strrpos((string) $haystack, (string) $needle);
    }
}

?>
