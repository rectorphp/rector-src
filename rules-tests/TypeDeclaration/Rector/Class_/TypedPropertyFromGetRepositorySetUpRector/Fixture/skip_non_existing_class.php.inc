<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNonExistingClass extends TestCase
{
    /**
     * @var \Some\Non\Existing\Repository
     */
    private $someEntityRepository;

    protected function setUp(): void
    {
        $this->someEntityRepository = $this->em->getRepository(SomeEntity::class);
    }
}
