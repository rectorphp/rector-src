<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionMethod;

function standaloneReflectionCall(): void
{
    $reflectionMethod = new ReflectionMethod('Foo', 'bar');
    $reflectionMethod->setAccessible(true);
    $reflectionMethod->invoke(null);
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionMethod;

function standaloneReflectionCall(): void
{
    $reflectionMethod = new ReflectionMethod('Foo', 'bar');
    $reflectionMethod->invoke(null);
}

?>
