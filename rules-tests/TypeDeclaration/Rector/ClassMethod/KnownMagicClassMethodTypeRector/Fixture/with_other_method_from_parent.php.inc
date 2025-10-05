<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Source\ParentClassOtherMethod;

final class WithOtherMethodFromParent extends ParentClassOtherMethod
{
    public function __invoke()
    {
    }

    public function __call($method, $args)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector\Source\ParentClassOtherMethod;

final class WithOtherMethodFromParent extends ParentClassOtherMethod
{
    public function __invoke()
    {
    }

    public function __call(string $method, array $args)
    {
    }
}

?>
