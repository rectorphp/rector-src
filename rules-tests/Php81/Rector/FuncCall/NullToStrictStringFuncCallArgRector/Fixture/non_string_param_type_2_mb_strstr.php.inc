<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrstr
{
    public function run($haystack, $needle)
    {
        mb_strstr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrstr
{
    public function run($haystack, $needle)
    {
        mb_strstr((string) $haystack, (string) $needle);
    }
}

?>
