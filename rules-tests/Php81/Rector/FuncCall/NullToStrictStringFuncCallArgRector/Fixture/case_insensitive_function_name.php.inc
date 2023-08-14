<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

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

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class CaseInsensitiveFunctionName
{
    public function run($var)
    {
        return strToLower((string) $var);
    }
}

?>
