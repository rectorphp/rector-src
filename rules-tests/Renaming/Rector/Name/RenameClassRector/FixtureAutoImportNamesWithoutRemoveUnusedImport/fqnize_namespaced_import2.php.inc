<?php

namespace Abc2;

class FqnizeNamespacedImport2
{
    public function run(\DateTime $d)
    {
    }
}

?>
-----
<?php

namespace Abc;

use DateTimeInterface;
class FqnizeNamespacedImport2
{
    public function run(DateTimeInterface $d)
    {
    }
}

?>
