<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

class RemoveEqualType
 {
     /** @var string|false|null */
     private static string|false|null $inputArgSeparator = null;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

class RemoveEqualType
 {
     private static string|false|null $inputArgSeparator = null;
}

?>
