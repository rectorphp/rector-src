<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionMethod;
use ReflectionProperty;

final class Fixture
{
    public function run(): void
    {
        $reflectionProperty = new ReflectionProperty($this, 'privateProperty');
        $reflectionProperty->setAccessible(true);
        $value = $reflectionProperty->getValue($this);

        $reflectionMethod = new ReflectionMethod($this, 'privateMethod');
        $reflectionMethod->setAccessible(false);
        $reflectionMethod->invoke($this);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionMethod;
use ReflectionProperty;

final class Fixture
{
    public function run(): void
    {
        $reflectionProperty = new ReflectionProperty($this, 'privateProperty');
        $value = $reflectionProperty->getValue($this);

        $reflectionMethod = new ReflectionMethod($this, 'privateMethod');
        $reflectionMethod->invoke($this);
    }
}

?>
