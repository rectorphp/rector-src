<?php

namespace Rector\Tests\Php81\Rector\Class_\FixtureUpperCaseSnakeCase\FixtureUpperCaseSnakeCase;

use Spatie\Enum\Enum;

/**
 * @method static self isOK()
 * @method static self needsReview()
 * @method static self isV2()
 * @method static self IS_OBSOLETE()
 */
class StatusEnum extends Enum
{
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Class_\FixtureUpperCaseSnakeCase\FixtureUpperCaseSnakeCase;

use Spatie\Enum\Enum;

enum StatusEnum : string
{
    case IS_OK = 'isOK';
    case NEEDS_REVIEW = 'needsReview';
    case IS_V_2 = 'isV2';
    case IS_OBSOLETE = 'IS_OBSOLETE';
}

?>
