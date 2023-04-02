<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\Fixture;

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

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\Fixture;

final class DemoFile
{
    public function run()
    {
        $array = ['foo' => 'bar'];

        return [...$array, 'baz' => 'xxx'];
    }
}

?>
