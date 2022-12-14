<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Source\ParentAlreadyReadonly;

/**
 * Even class is not final, if extends class that already readonly, it must be readonly as well
 */
class ChildExtendsParentAlreadyReadonly extends ParentAlreadyReadonly
{
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

use Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Source\ParentAlreadyReadonly;

/**
 * Even class is not final, if extends class that already readonly, it must be readonly as well
 */
readonly class ChildExtendsParentAlreadyReadonly extends ParentAlreadyReadonly
{
}

?>
