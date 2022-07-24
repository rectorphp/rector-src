<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Source\ExternalList;

final class ForeachNonEmpty
{
    public function run(): array
    {
        $map = [];
        foreach (ExternalList::getArrayOfNonEmptyStrings() as $externalValue) {
            $map[$externalValue] = 100;
        }
        return $map;
    }
}
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Source\ExternalList;

final class ForeachNonEmpty
{
    /**
     * @return array<non-empty-string, int>
     */
    public function run(): array
    {
        $map = [];
        foreach (ExternalList::getArrayOfNonEmptyStrings() as $externalValue) {
            $map[$externalValue] = 100;
        }
        return $map;
    }
}
