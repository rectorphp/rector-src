<?php

namespace Rector\Tests\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class DemoFile
{
    public function foo(string $bar): array
    {
        mb_strlen("foo:{$bar}:");

    }
}

?>
