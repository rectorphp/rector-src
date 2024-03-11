<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDotsRector\Fixture;

use FilesystemIterator;

final class SkipUseVariable
{
    public function getIterator(): FilesystemIterator
    {
        $variable = FilesystemIterator::SKIP_DOTS;
        return new FilesystemIterator(__DIR__, $variable);
    }
}

?>
