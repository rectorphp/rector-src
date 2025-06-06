<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Source\StatusEnum;

class SkipEnumUsageInOtherPackage {
    public function isValid(StatusEnum $status): bool {
        return $status === StatusEnum::PENDING;
    }
}

?>
