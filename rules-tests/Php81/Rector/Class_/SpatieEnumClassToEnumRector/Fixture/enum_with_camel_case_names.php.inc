<?php

namespace Rector\Tests\Php81\Rector\Class_\SpatieEnumClassToEnumRector\Fixture;

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

namespace Rector\Tests\Php81\Rector\Class_\SpatieEnumClassToEnumRector\Fixture;

use Spatie\Enum\Enum;

enum StatusEnum : string
{
    case ISOK = 'isOK';
    case NEEDSREVIEW = 'needsReview';
    case ISV2 = 'isV2';
    case IS_OBSOLETE = 'IS_OBSOLETE';
}

?>
