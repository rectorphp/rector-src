<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

function binaryOrNegationOtherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0, 100) || ! $someClass) {
        return 'yes';
    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

function binaryOrNegationOtherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0, 100) || ! $someClass instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance) {
        return 'yes';
    }

    return 'no';
}

?>
