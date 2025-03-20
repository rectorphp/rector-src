<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

class RemoveFilterVarOnExactType
{
    public function run(int $number)
    {
        return filter_var($number, FILTER_VALIDATE_INT);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

class RemoveFilterVarOnExactType
{
    public function run(int $number)
    {
        return $number;
    }
}

?>
