<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strrpos
{
    public function run($haystack, $needle)
    {
        strrpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strrpos
{
    public function run($haystack, $needle)
    {
        strrpos((string) $haystack, (string) $needle);
    }
}

?>
