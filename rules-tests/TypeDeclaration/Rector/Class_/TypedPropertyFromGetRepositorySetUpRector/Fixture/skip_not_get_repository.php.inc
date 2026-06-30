<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Source\SomeEntityRepository;

final class SkipNotGetRepository extends TestCase
{
    /**
     * @var SomeEntityRepository
     */
    private $someEntityRepository;

    protected function setUp(): void
    {
        $this->someEntityRepository = new SomeEntityRepository();
    }
}
