<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixturePhp80;

final class AddDefaultFalseToBool
{
    public $name;

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
    public function __construct(public string|false $name = false)
    {
    }
}

?>
