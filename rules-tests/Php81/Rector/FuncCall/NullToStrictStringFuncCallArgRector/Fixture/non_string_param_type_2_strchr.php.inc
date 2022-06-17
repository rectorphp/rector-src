<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strchr
{
    public function run($haystack, $needle)
    {
        strchr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strchr
{
    public function run($haystack, $needle)
    {
        strchr((string) $haystack, (string) $needle);
    }
}

?>
