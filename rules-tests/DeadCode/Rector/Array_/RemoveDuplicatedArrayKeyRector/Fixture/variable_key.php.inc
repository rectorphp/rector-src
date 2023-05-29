<?php

namespace Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\Fixture;

class VariableKey
{
    public function lists()
    {
        $key = 1;

        $items = [
            $key => 'A',
            $key => 'A',
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector\Fixture;

class VariableKey
{
    public function lists()
    {
        $key = 1;

        $items = [
            $key => 'A',
        ];
    }
}

?>
