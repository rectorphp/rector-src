<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Source\FluentClass;

class Casted
{
    public function run()
    {
        (int) $this->processFluentClass((new FluentClass())->someFunction()->otherFunction2());
    }

    public function processFluentClass($value)
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Source\FluentClass;

class Casted
{
    public function run()
    {
        $fluentClass = new FluentClass();
        ($fluentClass)->someFunction();
        $fluentClass->otherFunction2();
        (int) $this->processFluentClass($fluentClass);
    }

    public function processFluentClass($value)
    {
        return $this;
    }
}

?>