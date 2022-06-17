<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CollatorCompare
{
    public function run($object, $string1, $string2)
    {
        collator_compare($object, $string1, $string2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CollatorCompare
{
    public function run($object, $string1, $string2)
    {
        collator_compare($object, (string) $string1, (string) $string2);
    }
}

?>
