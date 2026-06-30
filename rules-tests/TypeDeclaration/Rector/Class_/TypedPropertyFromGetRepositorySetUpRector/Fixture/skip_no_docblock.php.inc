<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipNoDocblock extends TestCase
{
    private $someEntityRepository;

    protected function setUp(): void
    {
        $this->someEntityRepository = $this->em->getRepository(SomeEntity::class);
    }
}
