<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum StatusEnum
{
    case PENDING;
    case published;
    case IN_REVIEW;
    case waiting_for_approval;
}


if (StatusEnum::PENDING) {
    echo 'PENDING';
}
?>
