<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class NullableProperty
{
    /**
     * @var int[]|null
     */
    public ?array $bar;

    public function setBar($bar): void
    {
        $this->bar = $bar;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class NullableProperty
{
    /**
     * @var int[]|null
     */
    public ?array $bar;

    public function setBar(?array $bar): void
    {
        $this->bar = $bar;
    }
}

?>
