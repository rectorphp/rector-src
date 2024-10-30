<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassNoArgsRector\Fixture;

class Fixture
{
    public function test()
    {
        echo get_class();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\FuncCall\RemoveGetClassNoArgsRector\Fixture;

class Fixture
{
    public function test()
    {
        echo __CLASS__;
    }
}

?>
