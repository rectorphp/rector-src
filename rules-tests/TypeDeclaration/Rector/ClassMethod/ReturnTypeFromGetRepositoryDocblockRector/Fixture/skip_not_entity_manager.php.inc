<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository;

final class SkipNotEntityManager
{
    public function __construct(private \stdClass $registry)
    {
    }

    /**
     * @return EventRepository
     */
    public function getRepository()
    {
        return $this->registry->getRepository(Event::class);
    }
}
