<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\StrvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = strval($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\StrvalToTypeCastRector\Fixture;

class Fixture
{
    public function run($value)
    {
        $value = (string) $value;
    }
}

?>
