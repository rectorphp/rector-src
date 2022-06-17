<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CollatorGetSortKey
{
    public function run($object, $string)
    {
        collator_get_sort_key($object, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2CollatorGetSortKey
{
    public function run($object, $string)
    {
        collator_get_sort_key($object, (string) $string);
    }
}

?>
