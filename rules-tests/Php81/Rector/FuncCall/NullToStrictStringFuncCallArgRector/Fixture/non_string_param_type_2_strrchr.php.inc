<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strrchr
{
    public function run($haystack, $needle)
    {
        strrchr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strrchr
{
    public function run($haystack, $needle)
    {
        strrchr((string) $haystack, (string) $needle);
    }
}

?>
