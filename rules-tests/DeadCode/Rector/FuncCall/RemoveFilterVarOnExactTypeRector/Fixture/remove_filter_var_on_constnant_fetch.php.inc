<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

final class RemoveFilterVarOnConstantFetch
{
    private const NUMBER = 123;

    public function run()
    {
        return filter_var(self::NUMBER, FILTER_VALIDATE_INT);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector\Fixture;

final class RemoveFilterVarOnConstantFetch
{
    private const NUMBER = 123;

    public function run()
    {
        return self::NUMBER;
    }
}

?>
