<?php

declare(strict_types=1);

namespace Rector\Core\Tests\DependencyInjection;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\Configuration\RenamedClassesDataCollector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class ConfigurableRectorImportConfigCallsMergeTest extends AbstractTestCase
{
    /**
     * @param array<string, string> $expectedConfiguration
     */
    #[DataProvider('provideData')]
    public function testMainConfigValues(string $configFile, array $expectedConfiguration): void
    {
        $this->bootFromConfigFiles([$configFile]);

        // to invoke configure() method call
        $renameClassRector = $this->getService(RenameClassRector::class);
        $this->assertInstanceOf(RenameClassRector::class, $renameClassRector);

        /** @var RenamedClassesDataCollector $renamedClassesDataCollector */
        $renamedClassesDataCollector = $this->getService(RenamedClassesDataCollector::class);

        $this->assertSame($expectedConfiguration, $renamedClassesDataCollector->getOldToNewClasses());
    }

    public static function provideData(): Iterator
    {
        yield [
            __DIR__ . '/config/main_config_with_override_value.php', [
                'old_2' => 'new_2',
                'old_1' => 'new_1',
                'old_4' => 'new_4',
            ],
        ];
<<<<<<< HEAD
=======

        yield [
<<<<<<< HEAD
=======
            __DIR__ . '/config/one_set.php', [
                'PHPUnit_Framework_MockObject_Stub' => 'PHPUnit\Framework\MockObject\Stub',
                'PHPUnit_Framework_MockObject_Stub_Return' => 'PHPUnit\Framework\MockObject\Stub\ReturnStub',
                'PHPUnit_Framework_MockObject_Matcher_Parameters' => 'PHPUnit\Framework\MockObject\Matcher\Parameters',
                'PHPUnit_Framework_MockObject_Matcher_Invocation' => 'PHPUnit\Framework\MockObject\Matcher\Invocation',
                'PHPUnit_Framework_MockObject_MockObject' => 'PHPUnit\Framework\MockObject\MockObject',
                'PHPUnit_Framework_MockObject_Invocation_Object' => 'PHPUnit\Framework\MockObject\Invocation\ObjectInvocation',
            ],
        ];

        yield [
>>>>>>> 10306a6c2c (simplify)
            __DIR__ . '/config/one_set_with_own_rename.php', [
                'Old' => 'New',
                'PHPUnit_Framework_MockObject_Stub' => 'PHPUnit\Framework\MockObject\Stub',
                'PHPUnit_Framework_MockObject_Stub_Return' => 'PHPUnit\Framework\MockObject\Stub\ReturnStub',
                'PHPUnit_Framework_MockObject_Matcher_Parameters' => 'PHPUnit\Framework\MockObject\Matcher\Parameters',
                'PHPUnit_Framework_MockObject_Matcher_Invocation' => 'PHPUnit\Framework\MockObject\Matcher\Invocation',
                'PHPUnit_Framework_MockObject_MockObject' => 'PHPUnit\Framework\MockObject\MockObject',
                'PHPUnit_Framework_MockObject_Invocation_Object' => 'PHPUnit\Framework\MockObject\Invocation\ObjectInvocation',
            ],
        ];
<<<<<<< HEAD
=======

        yield [
<<<<<<< HEAD
            __DIR__ . '/config/two_sets_with_own_rename.php', [
                'Old' => 'New',
=======
            __DIR__ . '/config/two_sets.php', [
>>>>>>> 10306a6c2c (simplify)
                'Twig_SimpleFilter' => 'Twig_Filter',
                'Twig_SimpleFunction' => 'Twig_Function',
                'Twig_SimpleTest' => 'Twig_Test',
                'PHPUnit_Framework_MockObject_Stub' => 'PHPUnit\Framework\MockObject\Stub',
                'PHPUnit_Framework_MockObject_Stub_Return' => 'PHPUnit\Framework\MockObject\Stub\ReturnStub',
                'PHPUnit_Framework_MockObject_Matcher_Parameters' => 'PHPUnit\Framework\MockObject\Matcher\Parameters',
                'PHPUnit_Framework_MockObject_Matcher_Invocation' => 'PHPUnit\Framework\MockObject\Matcher\Invocation',
                'PHPUnit_Framework_MockObject_MockObject' => 'PHPUnit\Framework\MockObject\MockObject',
                'PHPUnit_Framework_MockObject_Invocation_Object' => 'PHPUnit\Framework\MockObject\Invocation\ObjectInvocation',
            ],
        ];
>>>>>>> 1e53612c86 (simplify)
>>>>>>> 2cb7da5085 (simplify)
    }
}
