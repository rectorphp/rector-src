<?php

declare(strict_types=1);

namespace Rector\Core\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Rector\Core\DependencyInjection\DefinitionFinder;
use Rector\Core\DependencyInjection\Exception\DefinitionForTypeNotFoundException;
use stdClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class DefinitionFinderTest extends TestCase
{
    private ContainerBuilder $containerBuilder;

    private DefinitionFinder $definitionFinder;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->definitionFinder = new DefinitionFinder();
    }

    public function testAutowired(): void
    {
        $definition = $this->containerBuilder->autowire(stdClass::class);

        $stdClassDefinition = $this->definitionFinder->getByType($this->containerBuilder, stdClass::class);
        $this->assertSame($definition, $stdClassDefinition);
    }

    public function testNonAutowired(): void
    {
        $definition = $this->containerBuilder->register(stdClass::class);

        $foundStdClass = $this->definitionFinder->getByType($this->containerBuilder, stdClass::class);
        $this->assertSame($definition, $foundStdClass);
    }

    public function testMissing(): void
    {
        $this->expectException(DefinitionForTypeNotFoundException::class);
        $this->definitionFinder->getByType($this->containerBuilder, stdClass::class);
    }
}
