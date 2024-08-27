<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromArgClassStringRector\Source\SimpleContainer;

SimpleContainer::someCall(anotherCallback: fn ($var) => $var, callback: fn($var) => $var);
