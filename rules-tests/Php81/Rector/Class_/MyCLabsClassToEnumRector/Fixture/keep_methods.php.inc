<?php

namespace Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Fixture;

use MyCLabs\Enum\Enum;

final class KeepMethods extends Enum
{
    private const VIEW = 'view';

    private const EDIT = 'edit';

    public function isItSame(self $other): bool
    {
        return $this === $other;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Fixture;

use MyCLabs\Enum\Enum;

enum KeepMethods : string
{
    case VIEW = 'view';
    case EDIT = 'edit';
    public function isItSame(self $other): bool
    {
        return $this === $other;
    }
}

?>
