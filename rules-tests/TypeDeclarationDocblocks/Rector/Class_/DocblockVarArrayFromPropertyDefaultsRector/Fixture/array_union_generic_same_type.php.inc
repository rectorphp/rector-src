<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

use DateTime;
use stdClass;

class ArrayUnionGenericSameType
{
    protected array $dependencies = [
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
    protected array $dependencies = [
        stdClass::class,
        DateTime::class,
    ];
}

?>
