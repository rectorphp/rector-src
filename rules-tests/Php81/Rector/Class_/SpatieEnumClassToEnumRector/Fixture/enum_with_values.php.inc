<?php

namespace Rector\Tests\Php81\Rector\Class_\SpatieEnumClassToEnumRector\Fixture;

use Spatie\Enum\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 */
class StatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'draft' => 1,
            'published' => 2,
            'archived' => 3,
        ];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Class_\SpatieEnumClassToEnumRector\Fixture;

use Spatie\Enum\Enum;

enum StatusEnum : int
{
    case DRAFT = 1;
    case PUBLISHED = 2;
    case ARCHIVED = 3;
}

?>
