<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultFalse
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

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultFalse
{
    public function __construct(public string|false $name = false)
    {
    }
}

?>
