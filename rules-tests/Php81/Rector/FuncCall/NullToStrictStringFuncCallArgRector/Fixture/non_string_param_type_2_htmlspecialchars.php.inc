<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Htmlspecialchars
{
    public function run($string)
    {
        htmlspecialchars($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Htmlspecialchars
{
    public function run($string)
    {
        htmlspecialchars((string) $string);
    }
}

?>
