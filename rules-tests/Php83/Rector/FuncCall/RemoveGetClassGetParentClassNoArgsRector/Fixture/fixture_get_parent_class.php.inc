<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector\Fixture;

class Fixture
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

class Fixture
{
    public function test()
    {
        echo parent::class;
    }
}

?>
