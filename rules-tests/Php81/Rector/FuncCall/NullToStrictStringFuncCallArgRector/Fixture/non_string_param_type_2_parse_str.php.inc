<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ParseStr
{
    public function run($string, $result)
    {
        parse_str($string, $result);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ParseStr
{
    public function run($string, $result)
    {
        parse_str((string) $string, $result);
    }
}

?>
