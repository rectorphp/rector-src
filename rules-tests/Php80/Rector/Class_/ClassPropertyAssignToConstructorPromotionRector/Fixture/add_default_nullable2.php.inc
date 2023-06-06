<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable2
{
    public $name;

    public function __construct(string|false $name = null)
    {
        $this->name = $name;
    }
}

?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable2
{
    public function __construct(public string|null|false $name = null)
    {
    }
}

?>
