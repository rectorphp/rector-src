<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrrpos
{
    public function run($haystack, $needle)
    {
        mb_strrpos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbStrrpos
{
    public function run($haystack, $needle)
    {
        mb_strrpos((string) $haystack, (string) $needle);
    }
}

?>
