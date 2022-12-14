<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDots\Fixture;

use FilesystemIterator;

final class ConstantPresent
{
    public function getIterator(): FilesystemIterator
    {
        return new FilesystemIterator(__DIR__, FilesystemIterator::SKIP_DOTS);
    }
}

?>
