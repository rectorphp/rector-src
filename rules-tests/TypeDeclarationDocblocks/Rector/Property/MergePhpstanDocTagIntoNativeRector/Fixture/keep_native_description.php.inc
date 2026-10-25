<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class KeepNativeDescription
{
    /**
     * @return array some items
     * @phpstan-return list<string>
     */
    public function items(): array
    {
    }

    /**
     * @var array some values
     * @phpstan-var list<string>
     */
    private $values;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class KeepNativeDescription
{
    /**
     * @return list<string> some items
     */
    public function items(): array
    {
    }

    /**
     * @var list<string> some values
     */
    private $values;
}

?>
