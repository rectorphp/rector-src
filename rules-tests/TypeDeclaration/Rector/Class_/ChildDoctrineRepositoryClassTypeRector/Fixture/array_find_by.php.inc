<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;
use Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Source\SomeObject;

/**
 * @extends EntityRepository<SomeObject>
 */
final class ArrayFindBy extends EntityRepository
{
    public function findSome($userId)
    {
        return $this->findBy([
            'userId' => $userId,
        ]);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;
use Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Source\SomeObject;

/**
 * @extends EntityRepository<SomeObject>
 */
final class ArrayFindBy extends EntityRepository
{
    /**
     * @return SomeObject[]
     */
    public function findSome($userId): array
    {
        return $this->findBy([
            'userId' => $userId,
        ]);
    }
}

?>
