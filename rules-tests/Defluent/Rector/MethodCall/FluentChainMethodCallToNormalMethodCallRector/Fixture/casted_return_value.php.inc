<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source\DifferentReturnValues;

class CastedReturnValue
{
    public function getCastedValue(DifferentReturnValues $query)
    {
        return (int) $query->someFunction()->otherFunction();
    }
}

?>
-----
<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source\DifferentReturnValues;

class CastedReturnValue
{
    public function getCastedValue(DifferentReturnValues $query)
    {
        $query->someFunction();
        return (int) $query->otherFunction();
    }
}

?>
