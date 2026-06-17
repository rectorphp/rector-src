<?php

namespace App;

use App\Animal;
use App\Animal\Dog;

/**
 * @see Animal\Dog
 */
final class PartialDocblockNameKeepsNamespaceNotClassImport
{
}

?>
-----
<?php

namespace App;

use App\Animal;

/**
 * @see Animal\Dog
 */
final class PartialDocblockNameKeepsNamespaceNotClassImport
{
}

?>
