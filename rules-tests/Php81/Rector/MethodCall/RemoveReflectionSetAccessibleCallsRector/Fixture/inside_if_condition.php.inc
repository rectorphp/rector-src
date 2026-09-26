<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

final class InsideIfCondition
{
    public function run(bool $flag): void
    {
        $reflectionProperty = new ReflectionProperty('Foo', 'bar');

        if ($flag) {
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->getValue($this);
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\RemoveReflectionSetAccessibleCallsRector\Fixture;

use ReflectionProperty;

final class InsideIfCondition
{
    public function run(bool $flag): void
    {
        $reflectionProperty = new ReflectionProperty('Foo', 'bar');

        if ($flag) {
            $reflectionProperty->getValue($this);
        }
    }
}

?>
