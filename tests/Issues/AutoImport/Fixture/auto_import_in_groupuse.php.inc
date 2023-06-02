<?php

namespace Rector\Core\Tests\Issues\AutoImportGroupUse\Fixture;

use Rector\Core\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

final class AutoImportInGroupUse extends \Rector\Core\Tests\Issues\AutoImportGroupUse\Source\SomeClass
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportGroupUse\Fixture;

use Rector\Core\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

final class AutoImportInGroupUse extends SomeClass
{
}

?>
