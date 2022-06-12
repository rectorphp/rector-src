<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Rector\FileWithoutNamespace\CollectPSR4ComposerAutoloadNamespaceRenamesRector;

use Iterator;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class CollectPSR4ComposerAutoloadNamespaceRenamesRectorTest extends AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     * @param array<string, string> $expectedRenames
     */
    public function test(string $fixturePath, array $expectedRenames): void
    {
        /** @var RenamedClassesDataCollector $renamedClassesDataCollector */
        $renamedClassesDataCollector = $this->getService(RenamedClassesDataCollector::class);

        $smartFileInfo = new SmartFileInfo($fixturePath);
        $this->doTestFileInfo($smartFileInfo);

        $this->assertSame($expectedRenames, $renamedClassesDataCollector->getOldToNewClasses());
    }

    public function provideData(): Iterator
    {
        yield [
            __DIR__ . '/Fixture/case_insensitive.php.inc',
            [
                'Rector\\TestS\\PSR4\\RecTor\\fileWithoutNamespace\\NorMAliZeNamespaceByPSR4ComposerAutoloadRector\\fixture\\Foo' => 'Rector\\Tests\\PSR4\\Rector\\FileWithoutNamespace\\NormalizeNamespaceByPSR4ComposerAutoloadRector\\Fixture\\Foo',
            ],
        ];

        yield [
            __DIR__ . '/Fixture/do_not_change_next_namespace.php.inc',
            [
                'App\\execute' => 'Rector\\Tests\\PSR4\\Rector\\FileWithoutNamespace\\NormalizeNamespaceByPSR4ComposerAutoloadRector\\Fixture\\execute',
            ],
        ];

        yield [
            __DIR__ . '/Fixture/namespace_less_class.php.inc',
            [
                'NamespaceLessClass' => 'Rector\\Tests\\PSR4\\Rector\\FileWithoutNamespace\\NormalizeNamespaceByPSR4ComposerAutoloadRector\\Fixture\\NamespaceLessClass',
            ],
        ];

        yield [
            __DIR__ . '/Fixture/skip_already_defined_namespace.php.inc',
            [
            ],
        ];

        yield [
            __DIR__ . '/Fixture/wrong_namespace.php.inc',
            [
                'ThisIsWrong\\WrongNamespace' => 'Rector\\Tests\\PSR4\\Rector\\FileWithoutNamespace\\NormalizeNamespaceByPSR4ComposerAutoloadRector\\Fixture\\WrongNamespace',
            ],
        ];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/collect_namespace_renames_config.php';
    }
}
