<?php

declare(strict_types=1);

namespace Rector\Tests\NodeTypeResolver\DependencyInjection\BleedingEdgeIncludePurifier;

use Nette\Utils\FileSystem;
use Rector\NodeTypeResolver\DependencyInjection\BleedingEdgeIncludePurifier;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class BleedingEdgeIncludePurifierTest extends AbstractTestCase
{
    private BleedingEdgeIncludePurifier $bleedingEdgeIncludePurifier;

    protected function setUp(): void
    {
        $this->boot();

        $this->bleedingEdgeIncludePurifier = $this->getService(BleedingEdgeIncludePurifier::class);
    }

    public function testNothing(): void
    {
        $purifiedConfigFilePath = $this->bleedingEdgeIncludePurifier->purifyConfigFile(
            __DIR__ . '/Fixture/no_bleeding_edge.neon'
        );
        $this->assertNull($purifiedConfigFilePath);
    }

    public function test(): void
    {
        $purifiedConfigFilePath = $this->bleedingEdgeIncludePurifier->purifyConfigFile(
            __DIR__ . '/Fixture/some_file_including.neon'
        );

        $this->assertNotNull($purifiedConfigFilePath);

        $this->assertFileEquals(__DIR__ . '/Expected/some_file_including.neon', $purifiedConfigFilePath);

        // cleanup after yourself :)
        FileSystem::delete($purifiedConfigFilePath);
    }
}
