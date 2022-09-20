<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\FloatvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $a = floatval($value);
        $b = doubleval($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\FloatvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $a = (float) $value;
        $b = (float) $value;
    }
}

?>
