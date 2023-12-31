<?php

namespace Rector\Tests\Issues\AutoImportGroupUse\Fixture;

use Rector\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

final class AutoImportInGroupUse extends \Rector\Tests\Issues\AutoImportGroupUse\Source\SomeClass
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImportGroupUse\Fixture;

use Rector\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

final class AutoImportInGroupUse extends SomeClass
{
}

?>
