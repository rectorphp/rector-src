<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture;

use stdClass as SomeObject;

final class AutoImport extends \stdClass
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture;

use stdClass as SomeObject;

final class AutoImport extends SomeObject
{
}

?>
