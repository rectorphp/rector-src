<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

function binaryOr(?SomeInstance $someClass)
{
    if ($someClass || $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

function binaryOr(?SomeInstance $someClass)
{
    if ($someClass instanceof \Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance || $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

?>
