<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

final class DemoFile
{
    public function run()
    {
        $array = ['foo' => 'bar'];

        return array_merge(['baz' => 'xxx'], $array);
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
        $array = ['foo' => 'bar'];

        return ['baz' => 'xxx', ...$array];
    }
}

?>
