<?php

class DoNotMigrateScalarReference
{
    public function test(): int
    {
        return 1;
    }
}

?>
-----
<?php

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture;

class DoNotMigrateScalarReference
{
    public function test(): int
    {
        return 1;
    }
}

?>