<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Exception\ShouldNotHappenException;

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

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

use Rector\Exception\ShouldNotHappenException;

final class ThrowsException
{
    public function getValues(): void
    {
        throw new ShouldNotHappenException();
    }
}
?>
