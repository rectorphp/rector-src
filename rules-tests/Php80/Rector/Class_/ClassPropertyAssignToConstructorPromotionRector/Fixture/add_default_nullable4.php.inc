<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable4
{
    public $name;

    public function __construct(array|false $name = null)
    {
        $this->name = $name;
    }
}

?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable4
{
    public function __construct(public array|false|null $name = null)
    {
    }
}

?>
