<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

class SomeClass
{
    public function create()
    {
        $items['name'] = 'John';
        $items['surname'] = 'Doe';
        $items['age'] = 50;
        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector\Fixture;

class SomeClass
{
    public function create()
    {
        return ['name' => 'John', 'surname' => 'Doe', 'age' => 50];
    }
}

?>
