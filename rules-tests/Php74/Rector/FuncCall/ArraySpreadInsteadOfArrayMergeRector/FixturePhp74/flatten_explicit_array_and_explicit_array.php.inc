<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

final class DemoFile
{
    public function run()
    {
        return array_merge(['foo'], ['bar']);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

final class DemoFile
{
    public function run()
    {
        return ['foo', 'bar'];
    }
}

?>
