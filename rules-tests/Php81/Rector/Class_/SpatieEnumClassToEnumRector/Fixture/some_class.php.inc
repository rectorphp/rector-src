<?php

namespace Rector\Tests\Php81\Rector\Class_\SpatieEnumClassToEnumRector\Fixture;

use Spatie\Enum\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 * @method static self needsReview()
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
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case NEEDSREVIEW = 'needsReview';
}

?>
