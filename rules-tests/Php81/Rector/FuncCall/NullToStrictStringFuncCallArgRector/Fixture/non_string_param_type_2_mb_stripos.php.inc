<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStripos
{
    public function run($haystack, $needle)
    {
        mb_stripos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStripos
{
    public function run($haystack, $needle)
    {
        mb_stripos((string) $haystack, (string) $needle);
    }
}

?>
