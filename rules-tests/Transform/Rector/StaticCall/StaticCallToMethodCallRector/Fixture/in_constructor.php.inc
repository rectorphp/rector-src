<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Nette\Utils\FileSystem;

class InConstructor
{
    public function __construct()
    {
        return FileSystem::write('file', 'content');
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Nette\Utils\FileSystem;

class InConstructor
{
    public function __construct(\Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\TargetFileSystem $targetFileSystem)
    {
        return $targetFileSystem->dumpFile('file', 'content');
    }
}

?>
