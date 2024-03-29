<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$v = "whatever";
echo "${v}";

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$v = "whatever";
echo "{$v}";

?>
