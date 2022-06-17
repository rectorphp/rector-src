<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSubstrCount
{
    public function run($haystack, $needle)
    {
        mb_substr_count($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSubstrCount
{
    public function run($haystack, $needle)
    {
        mb_substr_count((string) $haystack, (string) $needle);
    }
}

?>
