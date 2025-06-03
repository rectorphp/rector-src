<?php

namespace App\SomeNameSpace;

use App\Interfaces\SomeInterface;

final class Fqcn implements SomeInterface
{
}

?>
-----
<?php

namespace App\SomeNameSpace;

use App\Interfaces\SomeInterface;

final class Fqcn implements \App\Interfaces\SomeOtherInterface
{
}

?>