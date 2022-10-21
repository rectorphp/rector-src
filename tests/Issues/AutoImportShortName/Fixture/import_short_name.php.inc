<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName;

class ImportShortName
{
    public function run(\DateTime $dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName;

use DateTime;
class ImportShortName
{
    public function run(DateTime $dateTime)
    {
    }
}

?>
