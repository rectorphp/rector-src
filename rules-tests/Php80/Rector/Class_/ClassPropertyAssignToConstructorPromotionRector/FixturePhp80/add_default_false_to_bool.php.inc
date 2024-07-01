<?php declare(strict_types = 1);

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
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixturePhp80;

final class AddDefaultFalseToBool
{
    public function __construct(public string|bool $name = false)
    {
    }
}

?>
    