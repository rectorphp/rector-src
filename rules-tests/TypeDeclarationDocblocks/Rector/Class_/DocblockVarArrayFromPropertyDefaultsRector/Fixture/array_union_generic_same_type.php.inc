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
     * @var array<int, class-string<\stdClass>|class-string<\DateTime>>
     */
    private array $dependencies = [
        stdClass::class,
        DateTime::class,
    ];
}

?>
