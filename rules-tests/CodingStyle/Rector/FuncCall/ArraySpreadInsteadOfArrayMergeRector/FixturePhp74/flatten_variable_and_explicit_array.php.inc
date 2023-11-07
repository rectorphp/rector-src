<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

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

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

final class DemoFile
{
    public function run()
    {
        $array = ['foo'];

        return [...$array, 'bar'];
    }
}

?>
