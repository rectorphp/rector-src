<?php

namespace Utils\Rector\Tests\Rector\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;
use Utils\Rector\Tests\Rector\ChildDoctrineRepositoryClassTypeRector\Source\SomeObject;

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

namespace Utils\Rector\Tests\Rector\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;
use Utils\Rector\Tests\Rector\ChildDoctrineRepositoryClassTypeRector\Source\SomeObject;

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
