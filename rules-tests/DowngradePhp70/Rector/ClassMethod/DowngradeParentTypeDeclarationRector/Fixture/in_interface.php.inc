<?php

namespace Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\Fixture;

interface SomeInterface
{
    public function run(): parent;
}

class ParentClass
{

}

class SomeClass extends ParentClass implements SomeInterface
{
    use SomeTrait;
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\Fixture;

interface SomeInterface
{
    /**
     * @return $this
     */
    public function run();
}

class ParentClass
{

}

class SomeClass extends ParentClass implements SomeInterface
{
    use SomeTrait;
}

?>
