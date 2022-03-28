<?php

namespace App;

/**
 * @\Doctrine\ORM\Mapping\Table("Table_Name")
 */
class WithNamespaceExists
{
}

?>
-----
<?php

namespace App;

use Doctrine\ORM\Mapping\Table;

/**
 * @Table("Table_Name")
 */
class WithNamespaceExists
{
}

?>
