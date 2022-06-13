<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

final class SkipUsePhpstanIgnoreNextLine
{
    // @phpstan-ignore-next-line
    public function run()
    {
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function run2()
    {
    }
}

?>
