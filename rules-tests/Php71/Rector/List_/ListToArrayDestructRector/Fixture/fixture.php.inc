<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

class Fixture
{
    public function run()
    {
        list($id1, $name1) = $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

class Fixture
{
    public function run()
    {
        [$id1, $name1] = $data;
    }
}

?>
