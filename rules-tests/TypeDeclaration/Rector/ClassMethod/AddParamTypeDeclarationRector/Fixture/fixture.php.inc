<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\Source\Contract\ParentInterfaceWithChangeTypeInterface;

class DetectedByParentInterface implements ParentInterfaceWithChangeTypeInterface
{
    public function process($name)
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\Source\Contract\ParentInterfaceWithChangeTypeInterface;

class DetectedByParentInterface implements ParentInterfaceWithChangeTypeInterface
{
    public function process(string $name)
    {
    }
}

?>
