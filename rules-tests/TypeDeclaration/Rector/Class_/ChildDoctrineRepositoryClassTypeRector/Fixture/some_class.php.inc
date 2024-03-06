<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;
use Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Source\SomeObject;

/**
 * @extends EntityRepository<SomeObject>
 */
final class ProductRepository extends EntityRepository
{
    public function findSome($userId)
    {
        return $this->findOneBy([
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
final class ProductRepository extends EntityRepository
{
    public function findSome($userId): ?SomeObject
    {
        return $this->findOneBy([
                'userId' => $userId,
        ]);
    }
}

?>
