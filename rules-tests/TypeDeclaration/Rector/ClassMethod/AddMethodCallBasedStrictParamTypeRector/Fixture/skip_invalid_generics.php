<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector\Fixture;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Container;

final class SkipInvalidGenerics extends TestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->get('entity_manager');
        $this->initDatabase($this->entityManager);
    }

    /**
     * @template TObject as object
     *
     * @param class-string<TObject> $type
     * @return TObject
     */
    public function get(string $type): object
    {
        $container = $this->getContainer();
        return $container->get($type);
    }

    private function initDatabase(EntityManagerInterface $entityManager)
    {
    }

    private function getContainer(): Container
    {
        return new Container();
    }
}
