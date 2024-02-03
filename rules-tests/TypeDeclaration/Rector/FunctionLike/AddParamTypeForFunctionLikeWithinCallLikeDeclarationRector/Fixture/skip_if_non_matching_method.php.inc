<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someOtherCall(function ($name) {
    return $name;
});
