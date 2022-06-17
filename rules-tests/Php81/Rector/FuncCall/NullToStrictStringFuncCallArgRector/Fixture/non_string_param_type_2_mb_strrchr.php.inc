<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrrchr
{
    public function run($haystack, $needle)
    {
        mb_strrchr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrrchr
{
    public function run($haystack, $needle)
    {
        mb_strrchr((string) $haystack, (string) $needle);
    }
}

?>
