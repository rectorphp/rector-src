<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function SkipNestedTernaryParenthesizedIf()
{
  (1 ? 2 : 3) ? 4 : 5;
}
