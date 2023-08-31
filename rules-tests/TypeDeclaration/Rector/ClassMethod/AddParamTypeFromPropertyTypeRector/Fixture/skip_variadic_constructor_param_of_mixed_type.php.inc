<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipVariadicConstructorParamOfMixedType
{
    /**
     * @var mixed[]
     */
    private array $elements;

    public function __construct(...$elements)
    {
        $this->elements = $elements;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipVariadicConstructorParamOfMixedType
{
    /**
     * @var mixed[]
     */
    private array $elements;

    public function __construct(...$elements)
    {
        $this->elements = $elements;
    }
}

?>
