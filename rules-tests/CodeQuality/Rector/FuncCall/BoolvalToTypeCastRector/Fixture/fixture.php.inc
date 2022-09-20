<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\BoolvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = boolval($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\BoolvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = (bool) $value;
    }
}

?>
