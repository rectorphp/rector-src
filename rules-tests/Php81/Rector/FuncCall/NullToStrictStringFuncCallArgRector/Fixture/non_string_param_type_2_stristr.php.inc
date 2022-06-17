<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Stristr
{
    public function run($haystack, $needle)
    {
        stristr($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Stristr
{
    public function run($haystack, $needle)
    {
        stristr((string) $haystack, (string) $needle);
    }
}

?>
