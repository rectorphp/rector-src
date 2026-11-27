<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayTest\Fixture;

goto end;
lbl:
$a = array(1);
end:

?>
-----
<?php

namespace Rector\Tests\Php54\Rector\Array_\LongArrayToShortArrayTest\Fixture;

goto end;
lbl:
$a = [1];
end:

?>
