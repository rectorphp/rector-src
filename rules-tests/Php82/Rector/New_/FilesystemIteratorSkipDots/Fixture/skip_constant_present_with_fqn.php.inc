<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDots\Fixture;

final class SkipConstantPresentWithFqn
{
    public function getIterator(): \FilesystemIterator
    {
        return new \FilesystemIterator(__DIR__, \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::KEY_AS_FILENAME);
    }
}

?>
