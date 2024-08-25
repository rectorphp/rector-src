<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgRector\Fixture;

use SomeNamespace\SomeClass;

SomeClass::someCall(fn() => 'test');
