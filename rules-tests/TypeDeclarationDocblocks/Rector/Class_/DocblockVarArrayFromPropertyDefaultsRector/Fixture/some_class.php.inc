<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class SomeClass
{
    private array $names = ['Jim', 'Rohn'];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class SomeClass
{
    /**
     * @var string[]
     */
    private array $names = ['Jim', 'Rohn'];
}

?>
