<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;

SimpleContainer::someCall(true, function ($object) {
    return $object;
});

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;

SimpleContainer::someCall(true, function (\Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer $object) {
    return $object;
});

?>
