<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\Source\ParentClass;

class ChildClass extends ParentClass
{
    public function getData()
    {
        return [];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\FollowParentReturnTypeDeclarationRector\Source\ParentClass;

class ChildClass extends ParentClass
{
    public function getData(): array
    {
        return [];
    }
}

?>
