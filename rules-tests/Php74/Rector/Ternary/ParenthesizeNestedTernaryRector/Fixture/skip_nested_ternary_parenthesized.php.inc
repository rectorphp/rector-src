<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function SkipNestedTernaryParenthesized()
{
  $a = 1;
  $b = 2;

//  $value = ($a ? $b : $a) ?: null;
  //(1 ? 2 : 3) ? 4 : 5;
  1 ? 2 : (3 ? 4 : 5);
}
