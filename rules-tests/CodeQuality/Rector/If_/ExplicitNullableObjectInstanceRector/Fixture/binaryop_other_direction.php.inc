<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

function otherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0,1) && $someClass) {
        return $someClass->someMethod();

    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

function otherDirection(?SomeInstance $someClass)
{
    if (mt_rand(0,1) && $someClass instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance) {
        return $someClass->someMethod();

    }

    return 'no';
}

?>
