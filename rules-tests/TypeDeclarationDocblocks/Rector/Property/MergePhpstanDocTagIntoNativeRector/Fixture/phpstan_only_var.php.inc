<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class PhpstanOnlyVar
{
    /**
     * @phpstan-var Collection<int, string>
     */
    private $items;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class PhpstanOnlyVar
{
    /**
     * @var Collection<int, string>
     */
    private $items;
}

?>
