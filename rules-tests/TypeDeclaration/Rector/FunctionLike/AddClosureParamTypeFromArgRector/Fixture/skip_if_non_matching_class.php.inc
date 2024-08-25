<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use SomeNamespace\SomeClass;

AnotherClass::someCall(function ($name) {
    return $name;
});
