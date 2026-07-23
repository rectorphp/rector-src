<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleSelfAssignRector\Fixture;

$validator = $validator = createValidator();

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveDoubleSelfAssignRector\Fixture;

$validator = createValidator();

?>
