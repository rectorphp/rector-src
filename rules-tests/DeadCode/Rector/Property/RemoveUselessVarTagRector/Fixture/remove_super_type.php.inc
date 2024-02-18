<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

class RemoveSuperType
 {
     /** @var string|bool|null */
     private static string|false|null $inputArgSeparator = null;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

class RemoveSuperType
 {
     private static string|false|null $inputArgSeparator = null;
}

?>