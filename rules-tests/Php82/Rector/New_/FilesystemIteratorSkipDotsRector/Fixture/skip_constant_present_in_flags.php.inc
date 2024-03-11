<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDotsRector\Fixture;

use FilesystemIterator;

final class SkipConstantPresentInFlags
{
    public function getIterator(): FilesystemIterator
    {
        return new FilesystemIterator(__DIR__, FilesystemIterator::FOLLOW_SYMLINKS | FilesystemIterator::SKIP_DOTS | FilesystemIterator::KEY_AS_FILENAME);
    }
}

?>
