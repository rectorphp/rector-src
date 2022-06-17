<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrEndsWith
{
    public function run($haystack, $needle)
    {
        str_ends_with($haystack, $needle);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StrEndsWith
{
    public function run($haystack, $needle)
    {
        str_ends_with((string) $haystack, (string) $needle);
    }
}

?>
