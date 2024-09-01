<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;

SimpleContainer::someOtherCall(function ($name) {
    return $name;
});
