<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Source;

enum StatusEnum
{
    case PENDING;
    case published;
    case IN_REVIEW;
    case waiting_for_approval;
}

?>
