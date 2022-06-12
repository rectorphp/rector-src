<?php

use A\B\Bar;
use A\B\C as Baz;
use A\B\Foo\ { X };
use A\B\Bat\ { Y as Yz };
use Doctrine\ORM\Mapping;

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

    public function test3(): X
    {
        return new X;
    }

    public function test4(): Yz
    {
        return new Yz;
    }

    public function test5(): Mapping\Entity
    {
        return new Mapping\Entity;
    }
}

?>
-----
<?php

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\NormalizeNamespaceByPSR4ComposerAutoloadRector\Fixture;

use A\B\Bar;
use A\B\C as Baz;
use A\B\Foo\ { X };
use A\B\Bat\ { Y as Yz };
use Doctrine\ORM\Mapping;

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

    public function test3(): X
    {
        return new X;
    }

    public function test4(): Yz
    {
        return new Yz;
    }

    public function test5(): Mapping\Entity
    {
        return new Mapping\Entity;
    }
}

?>
