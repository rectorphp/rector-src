<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class MethodParamAndReturn
{
    /**
     * @param array $values
     * @phpstan-param array<int, string> $values
     *
     * @return array
     * @phpstan-return array<int, string>
     */
    public function process($values)
    {
        return $values;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Property\MergePhpstanDocTagIntoNativeRector\Fixture;

final class MethodParamAndReturn
{
    /**
     * @param array<int, string> $values
     *
     * @return array<int, string>
     */
    public function process($values)
    {
        return $values;
    }
}

?>
