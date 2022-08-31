<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function nestedTernaryNoParentheses()
{
  $a = 1;
  $b = 2;
  $c = 3;

  $value = $a ? $b : $a ?: null;
  $value = $a ? $b : $a ? $c : null;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function nestedTernaryNoParentheses()
{
  $a = 1;
  $b = 2;
  $c = 3;

  $value = ($a ? $b : $a) ?: null;
  $value = ($a ? $b : $a) ? $c : null;
}

?>
