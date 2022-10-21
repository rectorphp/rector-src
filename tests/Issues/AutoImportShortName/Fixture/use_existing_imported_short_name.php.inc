<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName;

use DateTime;

new \DateTime;

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImportShortName;

use DateTime;

new DateTime;

?>
