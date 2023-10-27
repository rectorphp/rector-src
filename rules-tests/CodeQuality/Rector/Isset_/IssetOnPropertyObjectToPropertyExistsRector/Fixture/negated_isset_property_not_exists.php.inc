<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class NegatedIssetPropertyNotExists
{
    public function run()
    {
        return ! isset($this->x);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class NegatedIssetPropertyNotExists
{
    public function run()
    {
        return !property_exists($this, 'x') || $this->x === null;
    }
}

?>
