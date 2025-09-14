<?php

namespace Rector\Tests\CodingStyle\Rector\Enum_\EnumCaseToPascalCaseRector\Fixture;

enum SkipExistingPascal
{
    case Pending;
    case Published;
    case InReview;
    case WaitingForApproval;
}
