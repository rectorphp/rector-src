<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function skipNotNestedTernary()
{
  $a = 1;
  $b = 2;

  $value = $a ? $b : $a;
}
