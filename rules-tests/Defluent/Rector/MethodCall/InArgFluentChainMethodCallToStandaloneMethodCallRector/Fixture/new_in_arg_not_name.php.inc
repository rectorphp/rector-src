<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Source\FluentClass;

class ValueObject
{
    public $class = FluentClass::class;
}

class NewInArgNotName
{
    public function someFunction(ValueObject $valueObject)
    {
        $this->processFluentClass(
            (new $valueObject->class)->someFunction()->otherFunction(),
            $valueObject->class
        );
    }

    public function processFluentClass($arg, $arg2)
    {
    }
}

?>
