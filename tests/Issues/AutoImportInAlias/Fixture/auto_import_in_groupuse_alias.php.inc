<?php

namespace Rector\Core\Tests\Issues\AutoImportInAlias\Fixture;

use Rector\Core\Tests\Issues\AutoImportInAlias\Source\{ SomeClass as SomeObject };

final class AutoImportInAlias extends \Rector\Core\Tests\Issues\AutoImportInAlias\Source\SomeClass
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportInAlias\Fixture;

use Rector\Core\Tests\Issues\AutoImportInAlias\Source\{ SomeClass as SomeObject };

final class AutoImportInAlias extends SomeObject
{
}

?>
