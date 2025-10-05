<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class GetAndSet
{
    public function __get($method)
    {
    }

    public function __set($method, $args)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class GetAndSet
{
    public function __get(string $method): mixed
    {
    }

    public function __set(string $method, mixed $args)
    {
    }
}

?>
