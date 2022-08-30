<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function SkipNestedTernaryParenthesizedElse()
{
  1 ? 2 : (3 ? 4 : 5);
}
