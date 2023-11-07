<?php

namespace Rector\Core\Tests\Issues\DowngradeNullableReflectionProperty\Fixture;

class UseNullableReflectionProperty
{
    public function run(?\ReflectionProperty $reflectionProperty)
    {
        if ($reflectionProperty->getAttributes('SomeAttribute')[0] ?? null) {
            return true;
        }

        return false;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\DowngradeNullableReflectionProperty\Fixture;

class UseNullableReflectionProperty
{
    public function run(?\ReflectionProperty $reflectionProperty)
    {
        if ((method_exists($reflectionProperty, 'getAttributes') ? $reflectionProperty->getAttributes('SomeAttribute') : [])[0] ?? null) {
            return true;
        }

        return false;
    }
}

?>
