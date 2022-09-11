<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

use Closure;

final class VarClosureAnnotationMissingNullPreferPrepend
{
    /** @var Closure(string, int, Closure(float):int):string */
    private ?Closure $tooltipGenerator = null;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

use Closure;

final class VarClosureAnnotationMissingNullPreferPrepend
{
    /** @var null|Closure(string, int, Closure(float):int):string */
    private ?Closure $tooltipGenerator = null;
}

?>
