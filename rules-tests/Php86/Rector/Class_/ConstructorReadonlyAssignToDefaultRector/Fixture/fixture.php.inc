<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class Fixture
{
    public readonly int $number;

    public function __construct()
    {
        $this->number = 100;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php86\Rector\Class_\ConstructorReadonlyAssignToDefaultRector\Fixture;

final class Fixture
{
    public readonly int $number = 100;

    public function __construct()
    {
    }
}

?>
