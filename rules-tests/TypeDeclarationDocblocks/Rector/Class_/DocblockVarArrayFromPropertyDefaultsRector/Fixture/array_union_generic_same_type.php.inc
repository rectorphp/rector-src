<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

use DateTime;
use stdClass;

class ArrayUnionGenericSameType
{
    private array $dependencies = [
        stdClass::class,
        DateTime::class,
    ];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

use DateTime;
use stdClass;

class ArrayUnionGenericSameType
{
    /**
     * @var array<class-string<\DateTime>|class-string<\stdClass>>
     */
    private array $dependencies = [
        stdClass::class,
        DateTime::class,
    ];
}

?>
