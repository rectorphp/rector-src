<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SubstrCompare
{
    public function run($string1, $string2, $offset)
    {
        substr_compare($string1, $string2, $offset);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SubstrCompare
{
    public function run($string1, $string2, $offset)
    {
        substr_compare((string) $string1, (string) $string2, $offset);
    }
}

?>
