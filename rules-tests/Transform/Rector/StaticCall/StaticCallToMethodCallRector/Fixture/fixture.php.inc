<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Nette\Utils\FileSystem;

class SomeClass
{
    public function run()
    {
        return FileSystem::write('file', 'content');
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Fixture;

use Nette\Utils\FileSystem;

class SomeClass
{
    public function __construct(private \Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\TargetFileSystem $targetFileSystem)
    {
    }
    public function run()
    {
        return $this->targetFileSystem->dumpFile('file', 'content');
    }
}

?>
