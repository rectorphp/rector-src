<?php

namespace App\Database;

use Config\Database;

/**
 * @param Forge $forge
 */
function foo(\App\Database\Forge $forge)
{

}

?>
-----
<?php

namespace App\Database;

use Config\Database;

/**
 * @param Forge $forge
 */
function foo(Forge $forge)
{

}

?>
