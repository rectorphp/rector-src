<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\NewFluentChainMethodCallToNonFluentRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\NewFluentChainMethodCallToNonFluentRector\Source\FluentInterfaceClass;
use stdClass;

class SkipUseArgFromPrevVariable
{
    public function someFunction2()
    {
        $different = new stdClass;
        (new FluentInterfaceClass())->someFunction($different)->otherFunction();
    }
}

?>
