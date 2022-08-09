<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class SkipEncapsed
{
    public function foo(string $bar): array
    {
        mb_strlen("foo:{$bar}:");
    }
}

?>
