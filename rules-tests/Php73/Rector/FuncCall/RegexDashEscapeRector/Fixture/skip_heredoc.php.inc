<?php

namespace Rector\Tests\Php73\Rector\FuncCall\RegexDashEscapeRector\Fixture;

final class SkipHeredoc
{
    const COMPAT_PATTERN = <<<'CODE_SAMPLE'
preg_match('#[\w-()]#')
CODE_SAMPLE;
}
