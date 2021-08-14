<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Source\ValueObject;

class NewInArgNotName
{
    public function someFunction(ValueObject $valueObject)
    {
        $this->processFluentClass(
            (new $valueObject->class)->someFunction()->otherFunction()
        );
    }

    public function processFluentClass($arg)
    {
    }
}

?>
