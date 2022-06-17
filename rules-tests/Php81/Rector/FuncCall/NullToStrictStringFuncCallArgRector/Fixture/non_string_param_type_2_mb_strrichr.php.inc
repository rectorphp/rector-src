<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrrichr
{
    public function run($haystack, $needle)
    {
        mb_strrichr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrrichr
{
    public function run($haystack, $needle)
    {
        mb_strrichr((string) $haystack, (string) $needle);
    }
}

?>
