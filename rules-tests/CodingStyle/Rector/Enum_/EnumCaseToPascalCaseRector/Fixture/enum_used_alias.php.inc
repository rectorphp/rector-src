<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture\Status as StatusAlias;

enum Status
{
    case PENDING;
}

class EnumUsage {
    public function isValid(StatusAlias $status): bool {
        return $status === StatusAlias::PENDING;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture\Status as StatusAlias;

enum Status
{
    case Pending;
}

class EnumUsage {
    public function isValid(StatusAlias $status): bool {
        return $status === StatusAlias::Pending;
    }
}

?>
