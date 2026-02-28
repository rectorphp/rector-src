<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class BothIntegers
{
    public function run(int $value)
    {
        return in_array($value, [1, 2, 3]);
    }
}
?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class BothIntegers
{
    public function run(int $value)
    {
        return in_array($value, [1, 2, 3], true);
    }
}
?>
