<?php

declare(strict_types=1);

namespace Rector\Tests\DependencyInjection;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\RenamedClassesDataCollector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ConfigurableRectorImportConfigCallsMergeTest extends AbstractLazyTestCase
{
    /**
     * @param array<string, string> $expectedConfiguration
     */
    #[DataProvider('provideData')]
    public function testMainConfigValues(string $configFile, array $expectedConfiguration): void
    {
        $this->bootFromConfigFiles([$configFile]);

        // to invoke configure() method call
        $renameClassRector = $this->make(RenameClassRector::class);
        $this->assertInstanceOf(RenameClassRector::class, $renameClassRector);

        /** @var RenamedClassesDataCollector $renamedClassesDataCollector */
        $renamedClassesDataCollector = $this->make(RenamedClassesDataCollector::class);

        $this->assertSame($expectedConfiguration, $renamedClassesDataCollector->getOldToNewClasses());
    }

    public static function provideData(): Iterator
    {
        yield [
            __DIR__ . '/config/main_config_with_override_value.php', [
                'old_2' => 'new_2',
                'old_4' => 'new_4',
                'old_1' => 'new_1',
            ],
        ];
    }
}
