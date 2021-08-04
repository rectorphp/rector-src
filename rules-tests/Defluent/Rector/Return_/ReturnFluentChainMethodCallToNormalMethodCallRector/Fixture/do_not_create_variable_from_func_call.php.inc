<?php

declare(strict_types=1);

namespace Rector\Tests\Defluent\Rector\Return_\ReturnFluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\Return_\ReturnFluentChainMethodCallToNormalMethodCallRector\Source\FluentInterfaceClass;

function helper(): FluentInterfaceClass
{
    return new FluentInterfaceClass();
}

class ReturnBasicDoubleFluent
{
    public function someFunction()
    {
        return helper()->someFunction()->otherFunction();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Defluent\Rector\Return_\ReturnFluentChainMethodCallToNormalMethodCallRector\Fixture;

use Rector\Tests\Defluent\Rector\Return_\ReturnFluentChainMethodCallToNormalMethodCallRector\Source\FluentInterfaceClass;

function helper(): FluentInterfaceClass
{
    return new FluentInterfaceClass();
}

class ReturnBasicDoubleFluent
{
    public function someFunction()
    {
        $fluentInterfaceClass = helper();
        $fluentInterfaceClass->someFunction();
        $fluentInterfaceClass->otherFunction();
        return $fluentInterfaceClass;
    }
}

?>
