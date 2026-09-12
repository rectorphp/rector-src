<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

$accessProperty = function (): void {
    $reflectionProperty = new ReflectionProperty('Foo', 'bar');
    $reflectionProperty->setAccessible(true);
    $reflectionProperty->getValue();
};

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

$accessProperty = function (): void {
    $reflectionProperty = new ReflectionProperty('Foo', 'bar');
    $reflectionProperty->getValue();
};

?>
