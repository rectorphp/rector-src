<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum Status
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

enum Status
{
    case Pending;
}

class EnumUsage {
    public function isValid(Status $status): bool {
        return $status === Status::Pending;
    }
}

?>
