<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\Fixture;

final class DemoFile
{
    public function run()
    {
        return array_merge(['foo' => 'bar'], ['baz' => 'xxx']);
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
        return ['foo' => 'bar', 'baz' => 'xxx'];
    }
}

?>
