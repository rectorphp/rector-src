<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

final class SetupPrivate
{
    public function setUp()
    {
        $this->initialValue = 123;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector\Fixture;

final class SetupPrivate
{
    public function setUp()
    {
        $this->initialValue = 123;
    }
}

?>
