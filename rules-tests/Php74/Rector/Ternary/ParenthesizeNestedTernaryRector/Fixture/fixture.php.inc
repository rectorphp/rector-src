<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function nestedTernaryNoParentheses()
{
  $a = 1;
  $b = 2;

  $value = $a ? $b : $a ?: null;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function nestedTernaryNoParentheses()
{
  $a = 1;
  $b = 2;

  $value = ($a ? $b : $a) ?: null;
}

?>
