<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector\Source\Contract\ParentInterfaceWithChangeTypeInterface;

class DetectedByParentInterface implements ParentInterfaceWithChangeTypeInterface
{
    public function notChanged($name)
    {
    }

    public function process($name)
    {
    }

    public function run($name)
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
    public function notChanged($name)
    {
    }

    public function process(string $name)
    {
    }

    public function run(string $name)
    {
    }
}

?>
