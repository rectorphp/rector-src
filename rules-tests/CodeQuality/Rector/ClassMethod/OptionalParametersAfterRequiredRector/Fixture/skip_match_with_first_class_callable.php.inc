<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Source\SomeClass;

final class SkipMatchWithFirstClassCallable
{
    public function run($type):void
    {
        match($type) {
        	'string' => SomeClass::fromString(...),
            'int' => function () {
                return SomeClass::fromInt(...);
            }
        };
    }
}
