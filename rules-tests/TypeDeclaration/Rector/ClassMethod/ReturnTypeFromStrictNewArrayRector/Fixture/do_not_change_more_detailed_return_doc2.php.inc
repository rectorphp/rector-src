<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DoNotChangeMoreDetailedReturnDoc2
{
 	/**
     * @return list<string>
     */
    public function getCsvHeaderArray() {
        $HeaderArray = [];
        $HeaderArray[] = i::_('Mwst');

        return $HeaderArray;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DoNotChangeMoreDetailedReturnDoc2
{
 	/**
     * @return list<string>
     */
    public function getCsvHeaderArray(): array {
        $HeaderArray = [];
        $HeaderArray[] = i::_('Mwst');

        return $HeaderArray;
    }
}

?>
