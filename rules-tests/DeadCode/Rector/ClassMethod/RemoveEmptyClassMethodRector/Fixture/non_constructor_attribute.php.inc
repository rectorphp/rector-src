<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

#[\Attribute()]
final class NonConstructorAttribute
{
    /**
     * @param class-string $className
     */
    public function run(string $className)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

#[\Attribute()]
final class NonConstructorAttribute
{
}

?>
