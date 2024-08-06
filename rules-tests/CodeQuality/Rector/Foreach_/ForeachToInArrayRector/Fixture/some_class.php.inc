<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

class SomeClass
{
    public function foreachWithElseNullable(array $items)
    {
        foreach ($items as $item) {
            // some comment
            if ($item == 'something') {
                return true;
            }
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Foreach_\ForeachToInArrayRector\Fixture;

class SomeClass
{
    public function foreachWithElseNullable(array $items)
    {
        return in_array('something', $items);
    }
}

?>
