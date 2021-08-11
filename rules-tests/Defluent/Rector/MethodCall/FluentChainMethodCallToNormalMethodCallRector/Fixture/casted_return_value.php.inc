<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source\DifferentReturnValues2;

class CastedReturnValue
{
    public function getCastedValue(DifferentReturnValues2 $differentReturnValues)
    {
        return (int) $differentReturnValues->someFunction()->otherFunction();
    }
}

?>
-----
<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector\Source\DifferentReturnValues2;

class CastedReturnValue
{
    public function getCastedValue(DifferentReturnValues2 $differentReturnValues)
    {
        $differentReturnValues->someFunction();
        return (int) $differentReturnValues->otherFunction();
    }
}

?>
