<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDots\Fixture;

final class AppendConstantToMultipleFlagsWithFqn
{
    public function getIterator(): \FilesystemIterator
    {
        return new \FilesystemIterator(__DIR__, \FilesystemIterator::KEY_AS_FILENAME | \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::CURRENT_MODE_MASK);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDots\Fixture;

final class AppendConstantToMultipleFlagsWithFqn
{
    public function getIterator(): \FilesystemIterator
    {
        return new \FilesystemIterator(__DIR__, \FilesystemIterator::KEY_AS_FILENAME | \FilesystemIterator::FOLLOW_SYMLINKS | \FilesystemIterator::CURRENT_MODE_MASK | \FilesystemIterator::SKIP_DOTS);
    }
}

?>
