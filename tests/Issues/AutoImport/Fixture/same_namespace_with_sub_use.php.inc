<?php

namespace App\Database;

use Config\Database;

/**
 * @param Forge $forge
 */
function same_namespace_with_sub_use(\App\Database\Forge $forge)
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
function same_namespace_with_sub_use(Forge $forge)
{

}

?>
