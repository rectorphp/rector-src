<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function SkipNestedTernaryParenthesizedCombine()
{
  (1 ? 2 : 3) ? 4 : 5;
}
