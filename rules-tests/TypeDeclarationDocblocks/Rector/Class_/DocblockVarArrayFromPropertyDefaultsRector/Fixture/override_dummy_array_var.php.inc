<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class OverrideDummyArrayVar
{
    private array $names = ['Jim', 'Rohn'];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class OverrideDummyArrayVar
{
    /**
     * @var string[]
     */
    private array $names = ['Jim', 'Rohn'];
}

?>
