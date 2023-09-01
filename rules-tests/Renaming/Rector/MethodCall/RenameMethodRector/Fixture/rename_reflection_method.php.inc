<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

final class RenameReflectionMethod
{
    public function run(\ReflectionMethod|\ReflectionFunction $reflectionMethod)
    {
        return $reflectionMethod->getTentativeReturnType();
    }
}

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\MethodCall\RenameMethodRector\Fixture;

final class RenameReflectionMethod
{
    public function run(\ReflectionMethod|\ReflectionFunction $reflectionMethod)
    {
        return $reflectionMethod->getReturnType();
    }
}

?>
