<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

final class DemoFile
{
    public function run()
    {
        $array = ['foo' => 'bar'];

        return array_merge($array, ['baz' => 'xxx']);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

final class DemoFile
{
    public function run()
    {
        $array = ['foo' => 'bar'];

        return [...$array, 'baz' => 'xxx'];
    }
}

?>
