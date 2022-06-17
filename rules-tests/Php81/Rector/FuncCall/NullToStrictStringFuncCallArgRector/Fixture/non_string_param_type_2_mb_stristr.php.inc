<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStristr
{
    public function run($haystack, $needle)
    {
        mb_stristr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStristr
{
    public function run($haystack, $needle)
    {
        mb_stristr((string) $haystack, (string) $needle);
    }
}

?>
