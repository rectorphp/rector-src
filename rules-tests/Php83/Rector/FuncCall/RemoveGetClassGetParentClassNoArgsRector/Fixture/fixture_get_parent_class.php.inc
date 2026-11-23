<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Fixture;

use Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Source\SomeParentClass;

class Fixture extends SomeParentClass
{
    public function test()
    {
        echo get_parent_class();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Fixture;

use Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Source\SomeParentClass;

class Fixture extends SomeParentClass
{
    public function test()
    {
        echo parent::class;
    }
}

?>
