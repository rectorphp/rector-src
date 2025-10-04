<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class IssetUnset
{
    public function __isset($key): bool
    {
        return true;
    }

    public function __unset($key): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

final class IssetUnset
{
    public function __isset(string $key): bool
    {
        return true;
    }

    public function __unset(string $key): void
    {
    }
}

?>
