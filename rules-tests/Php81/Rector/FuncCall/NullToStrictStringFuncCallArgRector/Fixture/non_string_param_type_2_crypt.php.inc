<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Crypt
{
    public function run($string, $salt)
    {
        crypt($string, $salt);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Crypt
{
    public function run($string, $salt)
    {
        crypt((string) $string, (string) $salt);
    }
}

?>
