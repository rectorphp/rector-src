<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class CaseInsensitiveFunctionName
{
    public function run($var)
    {
        return strToLower($var);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class CaseInsensitiveFunctionName
{
    public function run($var)
    {
        return strToLower((string) $var);
    }
}

?>
