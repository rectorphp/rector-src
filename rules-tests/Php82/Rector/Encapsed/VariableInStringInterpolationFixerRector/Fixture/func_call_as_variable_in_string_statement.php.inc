<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$c = "football";
function foo()
{
   return "c";
}
echo "I like playing ${foo()}";

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector\Fixture;

$c = "football";
function foo()
{
   return "c";
}
echo "I like playing {${foo()}}";

?>
