<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDotsRector\Fixture;

final class SkipRecursiveSkipDots
{
    public function run()
    {
        new \RecursiveDirectoryIterator(
            '/path/to/dir',
            \RecursiveDirectoryIterator::SKIP_DOTS
        );
    }
}

?>
