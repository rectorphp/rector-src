<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SubstrCount
{
    public function run($haystack, $needle)
    {
        substr_count($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SubstrCount
{
    public function run($haystack, $needle)
    {
        substr_count((string) $haystack, (string) $needle);
    }
}

?>
