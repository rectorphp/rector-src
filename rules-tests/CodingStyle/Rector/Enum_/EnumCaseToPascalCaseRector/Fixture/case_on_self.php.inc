<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum CaseOnSelf: string
{
    case PENDING = 'pending';

    function isPending(): bool
    {
        return $this === self::PENDING;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum CaseOnSelf: string
{
    case Pending = 'pending';

    function isPending(): bool
    {
        return $this === self::Pending;
    }
}

?>
