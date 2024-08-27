<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Source\NonTargetedClass;

NonTargetedClass::someCall(function ($name) {
    return $name;
});
