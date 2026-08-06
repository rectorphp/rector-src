<?php

declare(strict_types=1);

namespace Rector\Tests\Tia;

use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FixtureResolverTest extends TestCase
{
    private string $projectRoot;

    private FixtureResolver $fixtureResolver;

    protected function setUp(): void
    {
        $this->projectRoot = sys_get_temp_dir() . '/rector_tia_fixture_resolver';

        FileSystem::delete($this->projectRoot);

        foreach ([
            'rules-tests/SomeSet/Rector/If_/SomeRector/SomeRectorTest.php',
            'rules-tests/SomeSet/Rector/If_/SomeRector/Fixture/some_fixture.php.inc',
            'rules-tests/SomeSet/Rector/If_/SomeRector/Fixture/nested/deep_fixture.php.inc',
            'rules-tests/SomeSet/Rector/If_/SomeRector/Source/SomeSource.php',
            'rules-tests/SomeSet/Rector/If_/WithoutTestRector/Fixture/orphan.php.inc',
            'src/SomeService.php',
        ] as $relativeFilePath) {
            FileSystem::write($this->projectRoot . '/' . $relativeFilePath, '');
        }

        $this->fixtureResolver = new FixtureResolver();
    }

    protected function tearDown(): void
    {
        FileSystem::delete($this->projectRoot);
    }

    /**
     * @param string[] $expectedRelativeFilePaths
     */
    #[DataProvider('provideData')]
    public function testResolve(string $changedRelativePath, array $expectedRelativeFilePaths): void
    {
        $resolvedFilePaths = $this->fixtureResolver->resolve($this->projectRoot, $changedRelativePath);

        $expectedFilePaths = array_map(
            fn (string $relativeFilePath): string => $this->projectRoot . '/' . $relativeFilePath,
            $expectedRelativeFilePaths
        );

        $this->assertSame($expectedFilePaths, $resolvedFilePaths);
    }

    public static function provideData(): iterable
    {
        $testFilePath = 'rules-tests/SomeSet/Rector/If_/SomeRector/SomeRectorTest.php';

        yield 'fixture file resolves to the sibling test' => [
            'rules-tests/SomeSet/Rector/If_/SomeRector/Fixture/some_fixture.php.inc',
            [$testFilePath],
        ];

        yield 'nested fixture file walks up to the test' => [
            'rules-tests/SomeSet/Rector/If_/SomeRector/Fixture/nested/deep_fixture.php.inc',
            [$testFilePath],
        ];

        yield 'source file resolves to the sibling test' => [
            'rules-tests/SomeSet/Rector/If_/SomeRector/Source/SomeSource.php',
            [$testFilePath],
        ];

        yield 'test file itself resolves to itself' => [$testFilePath, [$testFilePath]];

        yield 'fixture without any test above it resolves to nothing' => [
            'rules-tests/SomeSet/Rector/If_/WithoutTestRector/Fixture/orphan.php.inc',
            [],
        ];

        yield 'file outside test directories is ignored' => ['src/SomeService.php', []];
    }
}
