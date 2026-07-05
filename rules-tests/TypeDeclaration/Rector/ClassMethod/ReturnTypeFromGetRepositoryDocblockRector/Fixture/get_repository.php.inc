<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository;

final class GetRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @return EventRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(Event::class);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository;

final class GetRepository
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function getRepository(): \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromGetRepositoryDocblockRector\Source\EventRepository
    {
        return $this->em->getRepository(Event::class);
    }
}

?>
