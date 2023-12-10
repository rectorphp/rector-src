<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class BaseClass
{
    public function run()
    {
        return SomeNamedConstructorClass::from();
    }
}

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

class SomeNamedConstructorClass {
	public static function from(): static
    {
     	return new static();
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

final class BaseClass
{
    public function run(): SomeNamedConstructorClass
    {
        return SomeNamedConstructorClass::from();
    }
}

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Fixture;

class SomeNamedConstructorClass {
	public static function from(): static
    {
     	return new static();
    }
}

?>
