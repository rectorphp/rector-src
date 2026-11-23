<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixturePhp80;

final class NullableString
{
    private $name;

    public function __construct(string $name = null)
    {
        $this->name = $name;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\FixturePhp80;

final class NullableString
{
    public function __construct(private ?string $name = null)
    {
    }
}

?>
