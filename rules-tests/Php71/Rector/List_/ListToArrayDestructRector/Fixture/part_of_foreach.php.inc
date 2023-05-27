<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class PartOfForeach
{
    public function run()
    {
        foreach ($data as list($id, $name)) {
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class PartOfForeach
{
    public function run()
    {
        foreach ($data as [$id, $name]) {
        }
    }
}

?>
