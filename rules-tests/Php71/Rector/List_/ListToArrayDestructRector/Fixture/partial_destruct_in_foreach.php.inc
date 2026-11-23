<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class PartialDestructInForeach
{
    public function run(array $rows)
    {
        foreach ($rows as list(, $second)) {
            echo $second;
        }

        list($first, , $third) = $rows;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php71\Rector\List_\ListToArrayDestructRector\Fixture;

final class PartialDestructInForeach
{
    public function run(array $rows)
    {
        foreach ($rows as [, $second]) {
            echo $second;
        }

        [$first, , $third] = $rows;
    }
}

?>
