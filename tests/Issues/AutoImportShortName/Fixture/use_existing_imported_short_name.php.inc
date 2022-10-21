<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;

new \DateTime;

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;

new DateTime;

?>
