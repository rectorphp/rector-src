<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Source\SomeEntityRepository;

final class SkipPublicProperty extends TestCase
{
    /**
     * @var SomeEntityRepository
     */
    public $someEntityRepository;

    protected function setUp(): void
    {
        $this->someEntityRepository = $this->em->getRepository(SomeEntity::class);
    }
}
