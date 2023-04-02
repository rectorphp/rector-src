<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $array = ['foo'];

        return array_merge($array, ['bar']);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $array = ['foo'];

        return [...$array, 'bar'];
    }
}

?>
