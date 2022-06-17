<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strripos
{
    public function run($haystack, $needle)
    {
        strripos($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Strripos
{
    public function run($haystack, $needle)
    {
        strripos((string) $haystack, (string) $needle);
    }
}

?>
