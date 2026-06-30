<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Source\SomeEntityRepository;

final class EmGetRepository extends TestCase
{
    /**
     * @var SomeEntityRepository
     */
    private $someEntityRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->someEntityRepository = $this->em->getRepository(SomeEntity::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Fixture;

use PHPUnit\Framework\TestCase;
use Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Source\SomeEntityRepository;

final class EmGetRepository extends TestCase
{
    private \Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector\Source\SomeEntityRepository $someEntityRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->someEntityRepository = $this->em->getRepository(SomeEntity::class);
    }
}

?>
