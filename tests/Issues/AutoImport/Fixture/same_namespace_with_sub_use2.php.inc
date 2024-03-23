<?php

namespace App\Database;

use Config\Database;

/**
 * @param \App\Database\Forge $forge
 */
function same_namespace_with_sub_use2(\App\Database\Forge $forge)
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
function same_namespace_with_sub_use2(Forge $forge)
{

}

?>
