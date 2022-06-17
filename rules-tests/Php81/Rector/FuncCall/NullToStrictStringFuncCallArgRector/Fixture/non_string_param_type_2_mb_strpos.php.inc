<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrpos
{
    public function run($haystack, $needle)
    {
        mb_strpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrpos
{
    public function run($haystack, $needle)
    {
        mb_strpos((string) $haystack, (string) $needle);
    }
}

?>
