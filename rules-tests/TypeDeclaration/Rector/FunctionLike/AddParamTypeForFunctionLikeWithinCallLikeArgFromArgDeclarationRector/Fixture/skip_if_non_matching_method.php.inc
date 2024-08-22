<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddParamTypeForFunctionLikeWithinCallLikeArgFromArgDeclarationRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someOtherCall(function ($name) {
    return $name;
});
