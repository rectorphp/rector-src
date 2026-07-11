<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class PropertyVar
{
    /**
     * @var Collection
     *
     * @phpstan-var Collection<int, string>
     */
    private $items;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class PropertyVar
{
    /**
     * @var Collection<int, string>
     */
    private $items;
}

?>
