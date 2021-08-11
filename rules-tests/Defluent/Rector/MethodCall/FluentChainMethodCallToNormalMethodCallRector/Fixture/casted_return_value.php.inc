<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source\DifferentReturnValues;

class CastedReturnValue
{
    protected function getCastedValue(DifferentReturnValues $query)
    {
        return (int) $query->someFunction()->otherFunction();
    }
}

?>
