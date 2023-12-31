<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;

new \DateTime;

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImportShortName\Fixture;

use DateTime;

new DateTime;

?>
