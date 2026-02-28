<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class BothStrings
{
    public function run(string $value)
    {
        return in_array($value, ['one', 'two', 'three']);
    }
}
?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class BothStrings
{
    public function run(string $value)
    {
        return in_array($value, ['one', 'two', 'three'], true);
    }
}
?>
