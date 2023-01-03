<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDots\Fixture;

use FilesystemIterator;

final class SkipWithDefaultValue
{
    public function getIterator(): FilesystemIterator
    {
        return new FilesystemIterator(__DIR__);
    }
}

?>
