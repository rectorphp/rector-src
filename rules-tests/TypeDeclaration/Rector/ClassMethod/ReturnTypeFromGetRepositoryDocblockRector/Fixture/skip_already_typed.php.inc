<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository;

final class SkipAlreadyTyped
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getRepository(): EventRepository
    {
        return $this->em->getRepository(Event::class);
    }
}
