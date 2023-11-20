<?php

declare(strict_types=1);

use A\B;

class FqnizeNamespacedWithUseWithDeclareStrictTypes
{
}

?>
-----
<?php

declare(strict_types=1);
namespace Abc;

use A\B;
class FqnizeNamespacedWithUseWithDeclareStrictTypes
{
}

?>
