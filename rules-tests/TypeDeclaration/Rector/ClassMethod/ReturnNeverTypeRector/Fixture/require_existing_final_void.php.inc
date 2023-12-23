<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;

final class RequireExistingFinalVoid
{
    public function run(): void
    {
        throw new ShouldNotHappenException('implement by child');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

use Rector\Core\Exception\ShouldNotHappenException;

final class RequireExistingFinalVoid
{
    public function run(): never
    {
        throw new ShouldNotHappenException('implement by child');
    }
}

?>
