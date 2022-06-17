<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregReplaceCallback
{
    public function run($pattern, $callback, $string)
    {
        mb_ereg_replace_callback($pattern, $callback, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbEregReplaceCallback
{
    public function run($pattern, $callback, $string)
    {
        mb_ereg_replace_callback((string) $pattern, $callback, (string) $string);
    }
}

?>
