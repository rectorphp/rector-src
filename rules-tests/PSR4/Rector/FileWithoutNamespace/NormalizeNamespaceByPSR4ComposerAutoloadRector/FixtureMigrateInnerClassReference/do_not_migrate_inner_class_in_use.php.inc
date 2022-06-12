<?php

use A\B\Bar;
use A\B\C as Baz;

class DoNotMigrateInnerClassInUse
{
    public function test(): Bar
    {
        return new Bar();
    }

    public function test2(): Baz
    {
        return new Baz;
    }
}

?>
-----
<?php

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture;

use A\B\Bar;
use A\B\C as Baz;

class DoNotMigrateInnerClassInUse
{
    public function test(): Bar
    {
        return new Bar();
    }

    public function test2(): Baz
    {
        return new Baz;
    }
}

?>
