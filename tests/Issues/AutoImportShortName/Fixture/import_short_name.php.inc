<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

class ImportShortName
{
    public function run(\DateTime $dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;
class ImportShortName
{
    public function run(DateTime $dateTime)
    {
    }
}

?>
