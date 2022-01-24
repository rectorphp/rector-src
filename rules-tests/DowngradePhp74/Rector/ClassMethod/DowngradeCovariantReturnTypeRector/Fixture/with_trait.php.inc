<?php

namespace Rector\Tests\DowngradePhp74\Rector\ClassMethod\DowngradeCovariantReturnTypeRector\Fixture;

class ParentClass
{
}

class ChildClass extends ParentClass
{
}

interface AnotherContainerInterface
{
    public function get(): ParentClass;
}

final class YetAnotherContainer implements AnotherContainerInterface
{
    use AnotherServiceLocatorTrait;
}

trait AnotherServiceLocatorTrait
{
    public function get(): ChildClass
    {
    }
}
?>
-----
<?php

namespace Rector\Tests\DowngradePhp74\Rector\ClassMethod\DowngradeCovariantReturnTypeRector\Fixture;

class ParentClass
{
}

class ChildClass extends ParentClass
{
}

interface AnotherContainerInterface
{
    public function get(): ParentClass;
}

final class YetAnotherContainer implements AnotherContainerInterface
{
    use AnotherServiceLocatorTrait;
}

trait AnotherServiceLocatorTrait
{
    public function get(): \ParentClass
    {
    }
}
?>
