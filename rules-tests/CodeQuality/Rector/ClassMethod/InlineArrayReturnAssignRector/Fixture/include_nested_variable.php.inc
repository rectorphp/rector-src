<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class IncludeNestedVariable
{
    public function create()
    {
        $items[0]['name'] = 'John';
        $items[0]['surname'] = 'Doe';
        $items[0]['age'] = 50;
        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

final class IncludeNestedVariable
{
    public function create()
    {
        return [['name' => 'John', 'surname' => 'Doe', 'age' => 50]];
    }
}

?>
