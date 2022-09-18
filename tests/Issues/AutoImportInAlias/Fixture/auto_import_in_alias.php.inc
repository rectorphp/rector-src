<?php

namespace Rector\Core\Tests\Issues\AutoImportDocInUse\Fixture;

use stdClass as SomeObject;

final class AutoImportInAlias extends \stdClass
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportDocInUse\Fixture;

use stdClass as SomeObject;

final class AutoImportInAlias extends SomeObject
{
}

?>
