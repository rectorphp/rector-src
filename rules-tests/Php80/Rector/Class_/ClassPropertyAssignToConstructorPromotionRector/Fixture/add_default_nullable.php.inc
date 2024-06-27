<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable
{
    public $name;

    public $surname;

    public function __construct(
        string $name = null,
        string|false $surname = null
    ) {
        $this->name = $name;
        $this->surname = $surname;
    }
}

?>
-----
<?php declare(strict_types = 1);

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

final class AddDefaultNullable
{
    public function __construct(public ?string $name = null, public string|false|null $surname = null)
    {
    }
}

?>
