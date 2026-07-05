<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository;

final class GetRepositoryViaVariable
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @return EventRepository
     */
    public function getRepository()
    {
        /** @var EventRepository $repo */
        $repo = $this->em->getRepository(Event::class);

        $repo->setDispatcher($this->dispatcher);

        return $repo;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository;

final class GetRepositoryViaVariable
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getRepository(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository
    {
        /** @var EventRepository $repo */
        $repo = $this->em->getRepository(Event::class);

        $repo->setDispatcher($this->dispatcher);

        return $repo;
    }
}

?>
