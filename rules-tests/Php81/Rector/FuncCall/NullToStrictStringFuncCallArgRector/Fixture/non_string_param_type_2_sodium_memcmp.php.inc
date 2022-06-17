<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumMemcmp
{
    public function run($string1, $string2)
    {
        sodium_memcmp($string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumMemcmp
{
    public function run($string1, $string2)
    {
        sodium_memcmp((string) $string1, (string) $string2);
    }
}

?>
