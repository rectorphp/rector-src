<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someOtherCall(function ($name) {
    return $name;
});
