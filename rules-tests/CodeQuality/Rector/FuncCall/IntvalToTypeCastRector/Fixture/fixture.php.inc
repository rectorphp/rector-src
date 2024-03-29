<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = intval($value);

        $value = intval($value, 10);

        $value = intval($value, 8);

        $value = array_map(intval(...), [$value]);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = (int) $value;

        $value = (int) $value;

        $value = intval($value, 8);

        $value = array_map(intval(...), [$value]);
    }
}

?>
