<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDotsRector\Fixture;

use FilesystemIterator;

final class AppendConstantToFlag
{
    public function getIterator(): FilesystemIterator
    {
        return new FilesystemIterator(__DIR__, FilesystemIterator::KEY_AS_FILENAME);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\New_\FilesystemIteratorSkipDotsRector\Fixture;

use FilesystemIterator;

final class AppendConstantToFlag
{
    public function getIterator(): FilesystemIterator
    {
        return new FilesystemIterator(__DIR__, FilesystemIterator::KEY_AS_FILENAME | \FilesystemIterator::SKIP_DOTS);
    }
}

?>
