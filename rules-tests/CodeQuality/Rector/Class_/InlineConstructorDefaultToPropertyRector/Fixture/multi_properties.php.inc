<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class MultiProperties
{
    private $thing1, $thing2, $thing3;

    public function __construct()
    {
        $this->thing1 = 1;
        $this->thing2 = 2;
        $this->thing3 = 3;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class MultiProperties
{
    private $thing1 = 1, $thing2 = 2, $thing3 = 3;

    public function __construct()
    {
    }
}

?>
