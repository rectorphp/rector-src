<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;

final class ThrowsException
{
    public function getValues()
    {
        throw new ShouldNotHappenException();
    }
}
?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;

final class ThrowsException
{
    public function getValues(): void
    {
        throw new ShouldNotHappenException();
    }
}
?>
