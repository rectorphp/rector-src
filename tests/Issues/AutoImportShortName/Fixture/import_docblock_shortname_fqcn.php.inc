<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

class ImportDocblockShortName
{
    /**
     * @param \DateTime $dateTime
     */
    public function run($dateTime)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;

class ImportDocblockShortName
{
    /**
     * @param DateTime $dateTime
     */
    public function run($dateTime)
    {
    }
}

?>
