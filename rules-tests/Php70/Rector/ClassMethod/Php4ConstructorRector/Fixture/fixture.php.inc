<?php

namespace Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Fixture;

abstract class InvalidFlagsConstructorClass2
{
    abstract function InvalidFlagsConstructorClass2();
}

final class SensitivePhp4ConstructorClass
{
    public function sensitivePhp4ConstructorClass()
    {
    }
}

final class SameNamedConstructorClass
{
    public function __construct()
    {

    }

    public function SameNamedConstructorClass()
    {

    }
}

final class SomePhp4ConstructorClass
{
    public function SomePhp4ConstructorClass()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\ClassMethod\Php4ConstructorRector\Fixture;

abstract class InvalidFlagsConstructorClass2
{
    abstract function InvalidFlagsConstructorClass2();
}

final class SensitivePhp4ConstructorClass
{
    public function __construct()
    {
    }
}

final class SameNamedConstructorClass
{
    public function __construct()
    {

    }

    public function SameNamedConstructorClass()
    {

    }
}

final class SomePhp4ConstructorClass
{
    public function __construct()
    {
    }
}

?>
