<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;

final class SkipNoDocblock
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getRepository()
    {
        return $this->em->getRepository(Event::class);
    }
}
