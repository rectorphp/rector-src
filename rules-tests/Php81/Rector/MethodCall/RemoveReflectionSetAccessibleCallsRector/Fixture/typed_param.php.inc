<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

final class TypedParam
{
    public function run(ReflectionProperty $reflectionProperty): void
    {
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->getValue();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

final class TypedParam
{
    public function run(ReflectionProperty $reflectionProperty): void
    {
        $reflectionProperty->getValue();
    }
}

?>
