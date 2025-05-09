<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum Status
{
    case PENDING;
    case published;
    case IN_REVIEW;
    case waiting_for_approval;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum Status
{
    case Pending;
    case Published;
    case InReview;
    case WaitingForApproval;
}

?>
