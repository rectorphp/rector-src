<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\SomeInstance;

/**
 * @param SomeInstance|null $someClass
 */
function skipBinaryOrPhpdoc($someClass)
{
    if ($someClass || $someClass->someMethod()) {
        return 'yes';
    }

    return 'no';
}

