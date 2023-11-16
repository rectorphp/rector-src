<?php

class FqnizeNamespacedImport
{
    public function run(DateTime $d)
    {
    }
}

?>
-----
<?php

namespace Abc;

use DateTimeInterface;
class FqnizeNamespacedImport
{
    public function run(DateTimeInterface $d)
    {
    }
}

?>
