<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumBase642bin
{
    public function run($string, $id, $ignore)
    {
        sodium_base642bin($string, $id, $ignore);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumBase642bin
{
    public function run($string, $id, $ignore)
    {
        sodium_base642bin((string) $string, $id, (string) $ignore);
    }
}

?>
