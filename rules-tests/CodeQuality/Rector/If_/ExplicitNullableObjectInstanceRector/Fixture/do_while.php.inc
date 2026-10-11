<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\CheckedClass;

class DoWhile
{
    public function run(?CheckedClass $someClass)
    {
        do {
            // do something
        } while ($someClass !== null);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\CheckedClass;

class DoWhile
{
    public function run(?CheckedClass $someClass)
    {
        do {
            // do something
        } while ($someClass instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\CheckedClass);
    }
}

?>
