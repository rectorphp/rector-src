<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

final class WithProperty
{
    private ?SomeInstance $someClass;

    public function run()
    {
        if ($this->someClass && $this->someClass->someMethod()) {
            return 'yes';
        }

        return 'no';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

final class WithProperty
{
    private ?SomeInstance $someClass;

    public function run()
    {
        if ($this->someClass instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance && $this->someClass->someMethod()) {
            return 'yes';
        }

        return 'no';
    }
}

?>
