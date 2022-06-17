<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrripos
{
    public function run($haystack, $needle)
    {
        mb_strripos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrripos
{
    public function run($haystack, $needle)
    {
        mb_strripos((string) $haystack, (string) $needle);
    }
}

?>
