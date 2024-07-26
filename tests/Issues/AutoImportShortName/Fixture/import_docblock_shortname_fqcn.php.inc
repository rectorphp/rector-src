<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

class InNoNamespaceShortName
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
class InNoNamespaceShortName
{
    /**
     * @param DateTime $dateTime
     */
    public function run($dateTime)
    {
    }
}

?>
