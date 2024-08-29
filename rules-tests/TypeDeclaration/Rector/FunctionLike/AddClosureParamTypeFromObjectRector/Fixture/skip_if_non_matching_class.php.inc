<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\NonTargetedClass;

NonTargetedClass::someCall(function ($name) {
    return $name;
});
