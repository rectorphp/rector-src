<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class IssetOnManyProperties
{
    private $x;

    private $y;

    public function run()
    {
        if (! isset($this->x, $this->y)) {
            return true;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class IssetOnManyProperties
{
    private $x;

    private $y;

    public function run()
    {
        if ($this->x === null && $this->y === null) {
            return true;
        }

        return false;
    }
}

?>
