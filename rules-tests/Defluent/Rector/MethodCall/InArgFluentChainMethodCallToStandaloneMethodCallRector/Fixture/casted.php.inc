<?php

namespace Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\MethodCall\InArgFluentChainMethodCallToStandaloneMethodCallRector\Source\FluentClass;

class Casted
{
    public function run()
    {
        $data = (int) $this->processFluentClass((new FluentClass())->someFunction()->otherFunction2());
    }

    public function processFluentClass($value)
    {
        return $value;
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
        $data = (int) $this->processFluentClass($fluentClass);
    }

    public function processFluentClass($value)
    {
        return $value;
    }
}

?>