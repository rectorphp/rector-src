<?php

namespace Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\Fixture;

class ParentClass
{
}

class Fixture extends ParentClass
{
    public function getParent(): parent
    {
        return $this;
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\Fixture;

class ParentClass
{
}

class Fixture extends ParentClass
{
    /**
     * @return \Rector\Tests\DowngradePhp70\Rector\ClassMethod\DowngradeParentTypeDeclarationRector\Fixture\ParentClass
     */
    public function getParent()
    {
        return $this;
    }
}

?>
