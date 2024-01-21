<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class DynamicArrayDefinition
{
    public function run()
    {
        $arr = [];
        $arr['foo']['bar']['baz'] = ['a' => 1, 'b' => 2];

        return $arr;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class DynamicArrayDefinition
{
    public function run()
    {
        return ['foo' => ['bar' => ['baz' => ['a' => 1, 'b' => 2]]]];
    }
}

?>
