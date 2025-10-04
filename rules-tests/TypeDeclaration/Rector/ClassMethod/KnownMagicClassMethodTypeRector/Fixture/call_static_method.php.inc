<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class CallStaticMethod
{
    public function __callStatic($method, $args)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class CallStaticMethod
{
    public function __callStatic(string $method, array $args)
    {
    }
}

?>
