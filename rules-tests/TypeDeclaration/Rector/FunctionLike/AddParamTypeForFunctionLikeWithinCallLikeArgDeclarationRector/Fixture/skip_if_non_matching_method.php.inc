<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someOtherCall(function ($name) {
    return $name;
});
