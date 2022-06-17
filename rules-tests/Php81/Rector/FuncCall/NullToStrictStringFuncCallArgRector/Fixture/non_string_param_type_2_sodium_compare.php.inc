<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCompare
{
    public function run($string1, $string2)
    {
        sodium_compare($string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCompare
{
    public function run($string1, $string2)
    {
        sodium_compare((string) $string1, (string) $string2);
    }
}

?>
