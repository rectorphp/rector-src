<?php

namespace Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Fixture;

use MyCLabs\Enum\Enum;
use Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Source\ComparingTrait;

final class KeepUsedTrait extends Enum
{
    use ComparingTrait;

    /**
     * Some comment
     */
    private const VIEW = 'view';

    private const EDIT = 'edit';
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Fixture;

use MyCLabs\Enum\Enum;
use Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Source\ComparingTrait;

enum KeepUsedTrait : string
{
    use ComparingTrait;
    /**
     * Some comment
     */
    case VIEW = 'view';
    case EDIT = 'edit';
}

?>
