<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;
use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance2;

function bothTruthy(?SomeInstance $someClass, ?SomeInstance2 $someClass2)
{
    if ($someClass && $someClass2) {
        return 'yes';
    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;
use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance2;

function bothTruthy(?SomeInstance $someClass, ?SomeInstance2 $someClass2)
{
    if ($someClass instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance && $someClass2 instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance2) {
        return 'yes';
    }

    return 'no';
}

?>
