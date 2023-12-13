<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source;

class SomeNamedConstructorClass {
	public static function from(): static
    {
     	return new static();
    }
}