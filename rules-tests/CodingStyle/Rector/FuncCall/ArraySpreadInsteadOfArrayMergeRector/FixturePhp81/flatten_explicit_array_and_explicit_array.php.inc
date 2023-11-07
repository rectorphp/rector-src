<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

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

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

final class DemoFile
{
    public function run()
    {
        return ['foo' => 'bar', 'baz' => 'xxx'];
    }
}

?>
