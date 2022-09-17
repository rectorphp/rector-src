<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector\FixturePhp80;

class Fixture
{
    public function run()
    {
        fn () => rand(0, 1) ? true : (rand(0, 1) ? [] : 0);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeRector\FixturePhp80;

class Fixture
{
    public function run()
    {
        fn (): array|bool|int => rand(0, 1) ? true : (rand(0, 1) ? [] : 0);
    }
}

?>
