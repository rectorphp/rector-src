<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class FqcnVarClosureVarAnnotationMissingNull
{
    /** @var \Closure(string, int, \Closure(float):int):string */
    private ?Closure $tooltipGenerator = null;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class FqcnVarClosureVarAnnotationMissingNull
{
    /** @var null|\Closure(string, int, \Closure(float): int): string */
    private ?Closure $tooltipGenerator = null;
}

?>
