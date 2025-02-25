<?php

namespace Rector\Tests\Php53\Rector\Ternary\TernaryToElvisRector\Fixture;

function parenthesesInElse()
{
    $new = $a ? $a : ($a !== false ? $b : null);
}

?>
-----
<?php

namespace Rector\Tests\Php53\Rector\Ternary\TernaryToElvisRector\Fixture;

function parenthesesInElse()
{
    $new = $a ?: ($a !== false ? $b : null);
}

?>
