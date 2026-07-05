<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;

final class SkipNonExistingRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @return \App\NonExisting\MissingRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(Event::class);
    }
}
