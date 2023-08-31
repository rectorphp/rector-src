<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipVariadicConstructorParamOfExplicitType
{
    /**
     * @var DateTime[]
     */
    private array $dates;

    public function __construct(...$dates) {
        $this->dates = $dates;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipVariadicConstructorParamOfExplicitType
{
    /**
     * @var DateTime[]
     */
    private array $dates;

    public function __construct(...$dates) {
        $this->dates = $dates;
    }
}

?>