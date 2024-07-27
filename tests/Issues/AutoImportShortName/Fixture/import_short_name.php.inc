<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

class ImportShortName
{
    public function run(\DateTime $dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;

class ImportShortName
{
    public function run(DateTime $dateTime)
    {
    }
}

?>
