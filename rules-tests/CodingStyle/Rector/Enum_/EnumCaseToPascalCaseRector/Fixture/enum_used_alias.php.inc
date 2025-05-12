<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture\StatusAlias as Status;

enum StatusAlias
{
    case PENDING;
}

class EnumUsage {
    public function isValid(Status $status): bool {
        return $status === Status::PENDING;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture\StatusAlias as Status;

enum StatusAlias
{
    case Pending;
}

class EnumUsage {
    public function isValid(Status $status): bool {
        return $status === Status::Pending;
    }
}

?>
