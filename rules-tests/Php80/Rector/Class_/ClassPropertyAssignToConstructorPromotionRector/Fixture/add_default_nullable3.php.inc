<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable3
{
    public $name;

    public function __construct(object|false $name = null)
    {
        $this->name = $name;
    }
}

?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable3
{
    public function __construct(public object|false|null $name = null)
    {
    }
}

?>
