<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source\Resource;

final class DoNotChangeMoreDetailedReturnDoc2
{
 	/**
     * @return list<string>
     */
    public function getCsvHeaderArray() {
        $HeaderArray = [];
        $HeaderArray[] = Resource::_('Mwst');

        return $HeaderArray;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Source\Resource;

final class DoNotChangeMoreDetailedReturnDoc2
{
 	/**
     * @return list<string>
     */
    public function getCsvHeaderArray(): array {
        $HeaderArray = [];
        $HeaderArray[] = Resource::_('Mwst');

        return $HeaderArray;
    }
}

?>
