<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\SimpleContainer;

SimpleContainer::someCall(\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\SomeType::class, function ($object) {
    return $object;
});

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\SimpleContainer;

SimpleContainer::someCall(\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\SomeType::class, function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Source\SomeType $object) {
    return $object;
});

?>
