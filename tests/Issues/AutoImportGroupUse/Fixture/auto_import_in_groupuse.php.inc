<?php

namespace Rector\Core\Tests\Issues\AutoImportGroupUse\Fixture;

use Rector\Core\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

final class AutoImportInAlias extends \Rector\Core\Tests\Issues\AutoImportGroupUse\Source\SomeClass
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportGroupUse\Fixture;

use Rector\Core\Tests\Issues\AutoImportGroupUse\Source\{ SomeClass };

final class AutoImportInAlias extends SomeClass
{
}

?>
