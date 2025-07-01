<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\FixturePublicOnInitialized;

class Fixture
{
    public function __construct()
    {
        $this->value = 5;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\FixturePublicOnInitialized;

class Fixture
{
    /**
     * @var int
     */
    public $value;
    public function __construct()
    {
        $this->value = 5;
    }
}

?>
