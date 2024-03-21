<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ChildDoctrineRepositoryClassTypeRector\Fixture;

use Doctrine\ORM\EntityRepository;

/**
 * @template T of object
 * @extends EntityRepository<T>
 */
final class ExtendedEntityRepository extends EntityRepository
{
    /**
     * Get an object or throws an exception
     *
     * @param array<string, mixed> $conditions
     *
     * @return T
     */
    public function findOneByOrThrow(array $conditions) {
        $entity = $this->findOneBy($conditions);
        if ($entity === null) {
            throw new \RuntimeException();
        }
        return $entity;
    }
}
