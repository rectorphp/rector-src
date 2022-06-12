<?php

class Fixture
{
    public function test(): Bar
    {
        return new Bar();
    }

    public function test2(): stdClass
    {
        return new stdClass;
    }
}

?>
-----
<?php

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture;

class Fixture
{
    public function test(): \Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture\Bar
    {
        return new \Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture\Bar();
    }

    public function test2(): \stdClass
    {
        return new \stdClass;
    }
}

?>
