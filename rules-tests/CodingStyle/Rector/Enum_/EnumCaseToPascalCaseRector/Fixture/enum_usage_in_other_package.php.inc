<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

class EnumUsageInOtherPackage {
    public function isValid(StatusEnum $status): bool {
        return $status === StatusEnum::PENDING;
    }
}

?>
