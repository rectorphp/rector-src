<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Source\SomeEntityRepository;

final class SkipNoTestCase
{
    /**
     * @var SomeEntityRepository
     */
    private $someEntityRepository;

    protected function setUp(): void
    {
        $this->someEntityRepository = $this->em->getRepository(SomeEntity::class);
    }
}
