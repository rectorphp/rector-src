<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;
use Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Source\SomeObject;

/**
 * @extends EntityRepository<SomeObject>
 */
final class GetOneOrNull extends EntityRepository
{
    public function findSome()
    {
        return $this->createQueryBuilder()
            ->getOneOrNullResult();
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
final class GetOneOrNull extends EntityRepository
{
    public function findSome(): ?SomeObject
    {
        return $this->createQueryBuilder()
            ->getOneOrNullResult();
    }
}

?>
