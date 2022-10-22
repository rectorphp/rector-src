<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

class SameLastNameNotImported
{
    public function run(\DateTime $dateTime)
    {
    }

    public function execute(\Rector\Core\Tests\Issues\AutoImportShortName\Source\DateTime $dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;
class SameLastNameNotImported
{
    public function run(DateTime $dateTime)
    {
    }

    public function execute(\Rector\Core\Tests\Issues\AutoImportShortName\Source\DateTime $dateTime)
    {
    }
}

?>
