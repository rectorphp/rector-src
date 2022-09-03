<?php

namespace Rector\Tests\Php74\Rector\Ternary\ParenthesizeNestedTernaryRector\Fixture;

function SkipNestedTernaryParenthesizedMultiline()
{
    is_dir('app')
        ? 'app'
        : (is_dir('vendor/app')
            ? 'vendor/app'
            : 'vendor/alternative');
}
