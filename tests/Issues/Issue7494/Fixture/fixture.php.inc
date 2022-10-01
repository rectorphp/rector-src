<?php

namespace Rector\Core\Tests\Issues\Issue7494\Fixture;

class Fixture
{
    private $dummyProperty;

    public function __construct()
    {
        $this->dummyProperty = 1;
    }

    private function getLowestPriceItem($value): int
    {
        $newValue = $value + 1;
        if ($value > 100) {
            return $value;
        } else {
            return $newValue;
        }
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\Issue7494\Fixture;

class Fixture
{
    private $dummyProperty;

    public function __construct()
    {
        $this->dummyProperty = 1;
    }

    private function getLowestPriceItem($value): int
    {
        $newValue = $value + 1;
        if ($value > 100) {
            return $value;
        }
        return $newValue;
    }
}

?>
