<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector\Fixture;

class Fixture
{
    public function run()
    {
        fn () => rand(0, 1) ? []: null;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector\Fixture;

class Fixture
{
    public function run()
    {
        fn (): ?array => rand(0, 1) ? []: null;
    }
}

?>
