<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDotsRector\Fixture;

use FilesystemIterator;

final class SkipWithDefaultValue
{
    public function getIterator(): FilesystemIterator
    {
        return new FilesystemIterator(__DIR__);
    }
}

?>
