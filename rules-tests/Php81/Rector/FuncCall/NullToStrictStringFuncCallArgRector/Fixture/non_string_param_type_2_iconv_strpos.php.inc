<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvStrpos
{
    public function run($haystack, $needle)
    {
        iconv_strpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvStrpos
{
    public function run($haystack, $needle)
    {
        iconv_strpos((string) $haystack, (string) $needle);
    }
}

?>
