<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ParseIniString
{
    public function run($iniString)
    {
        parse_ini_string($iniString);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2ParseIniString
{
    public function run($iniString)
    {
        parse_ini_string((string) $iniString);
    }
}

?>
