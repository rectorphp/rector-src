<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixturePhp80;

final class AddDefaultFalseToBool
{
    private $name;

    public function __construct(string $name = false)
    {
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixturePhp80;

final class AddDefaultFalseToBool
{
    public function __construct(private string|false $name = false)
    {
    }
}

?>
