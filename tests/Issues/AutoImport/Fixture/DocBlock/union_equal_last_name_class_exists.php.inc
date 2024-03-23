<?php

namespace App;

/**
 * @param \stdClass|\Rector\Tests\Issues\AutoImport\Source\stdClass $param
 */
function unionEqualLastNameClassExists($param)
{
}

?>
-----
<?php

namespace App;

use stdClass;
/**
 * @param stdClass|\Rector\Tests\Issues\AutoImport\Source\stdClass $param
 */
function unionEqualLastNameClassExists($param)
{
}

?>
