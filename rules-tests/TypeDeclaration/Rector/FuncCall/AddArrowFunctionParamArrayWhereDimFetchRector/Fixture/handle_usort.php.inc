<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class HandleUsort
{
    public function run(array $items)
    {
        usort(
            $items,
            fn ($a, $b): int => $a['key'] <=> $b['key']
        );
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrowFunctionParamArrayWhereDimFetchRector\Fixture;

final class HandleUsort
{
    public function run(array $items)
    {
        usort(
            $items,
            fn (array $a, array $b): int => $a['key'] <=> $b['key']
        );
    }
}

?>
