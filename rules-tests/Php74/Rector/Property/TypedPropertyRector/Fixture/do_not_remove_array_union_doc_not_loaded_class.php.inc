<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveArrayUnionDocNotLoadedClass
{
    /** @var string[]|\NotLoadedClass[] */
    private $unionValue;

    /** @var string[][]|\NotLoadedClass[][] */
    private $unionValue2;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveArrayUnionDocNotLoadedClass
{
    /** @var string[]|\NotLoadedClass[] */
    private array $unionValue;

    /** @var string[][]|\NotLoadedClass[][] */
    private array $unionValue2;
}

?>
