<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromObjectRector\Source\SimpleContainer;

SimpleContainer::someCall(anotherCallback: fn ($var) => $var, callback: fn($var) => $var);
