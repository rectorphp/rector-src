<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Source\SomeRepository;

final class HandleStaticCall
{
    public function getAll(): array
    {
        return SomeRepository::staticFindAll();
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockFromMethodCallDocblockRector\Source\SomeRepository;

final class HandleStaticCall
{
    /**
     * @return SomeEntity[]
     */
    public function getAll(): array
    {
        return SomeRepository::staticFindAll();
    }
}

?>
