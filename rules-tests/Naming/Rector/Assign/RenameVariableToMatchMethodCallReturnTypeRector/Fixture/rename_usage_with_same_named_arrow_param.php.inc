<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\SortableBuilder;

final class RenameUsageWithSameNamedArrowParam
{
    public function index(): array
    {
        $query = SortableBuilder::for(self::class)
            ->allowedSorts(fn (SortableBuilder $query): SortableBuilder => $query->defaultSort('x'))
            ->defaultSort('part_type');

        return $query->jsonPaginate();
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\SortableBuilder;

final class RenameUsageWithSameNamedArrowParam
{
    public function index(): array
    {
        $sortableBuilder = SortableBuilder::for(self::class)
            ->allowedSorts(fn (SortableBuilder $query): SortableBuilder => $query->defaultSort('x'))
            ->defaultSort('part_type');

        return $sortableBuilder->jsonPaginate();
    }
}

?>
