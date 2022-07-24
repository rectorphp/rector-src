<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Source\ExternalList;

final class ForeachNumeric
{
    public function run(): array
    {
        $map = [];
        foreach (ExternalList::NUMERIC_VALUES as $externalValue) {
            $map[$externalValue] = 100;
        }
        return $map;
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Source\ExternalList;

final class ForeachNumeric
{
    /**
     * @return array<numeric-string, int>
     */
    public function run(): array
    {
        $map = [];
        foreach (ExternalList::NUMERIC_VALUES as $externalValue) {
            $map[$externalValue] = 100;
        }
        return $map;
    }
}
