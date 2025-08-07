<?php

namespace Rector\Tests\Renaming\Rector\Cast\RenameCastRector\Fixture;

$real = (real) $real;
$double = (double) $double;

?>
-----
<?php

namespace Rector\Tests\Renaming\Rector\Cast\RenameCastRector\Fixture;

$real = (float) $real;
$double = (float) $double;

?>
