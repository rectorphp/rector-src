<?php

namespace Rector\Tests\CodeQuality\Rector\AssignOp\NewArrayItemConcatAssignToAssignRector\Fixture;

class NewArrayItem
{
    public function run($return_data, $type, $name)
    {
        $return_data[$type][] .= $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\AssignOp\NewArrayItemConcatAssignToAssignRector\Fixture;

class NewArrayItem
{
    public function run($return_data, $type, $name)
    {
        $return_data[$type][] = $name;
    }
}

?>
