<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

$value = random_int(0, 100);

max(0, min(100, $value));
max(0, min($value, 100));
max(min($value, 100), 0);
max(min(100, $value), 0);

min(100, max(0, $value));
min(100, max($value, 0));
min(max($value, 0), 100);
min(max(0, $value), 100);

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\FuncCall\MinMaxToClampRector\Fixture;

$value = random_int(0, 100);

clamp($value, 0, 100);
clamp($value, 0, 100);
clamp($value, 0, 100);
clamp($value, 0, 100);

clamp($value, 0, 100);
clamp($value, 0, 100);
clamp($value, 0, 100);
clamp($value, 0, 100);

?>
