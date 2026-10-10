<?php

declare(strict_types=1);

namespace Rector\Tests\Skipper\Skipper;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Skipper\Skipper\Skipper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\Skipper\Skipper\Fixture\Element\FifthElement;
use Rector\Tests\Skipper\Skipper\Source\AnotherClassToSkip;
use Rector\Tests\Skipper\Skipper\Source\NotSkippedClass;

final class SkipperTest extends AbstractLazyTestCase
{
    /**
     * Uniquely named on purpose, so the shared skipper is never polluted by another test.
     */
    private const string UNUSED_SKIP_MARKER = '*/UniqueUnusedSkipMarker/*';

    private Skipper $skipper;

    protected function setUp(): void
    {
        parent::setUp();

        SimpleParameterProvider::setParameter(Option::SKIP, [
            // windows like path
            '*\SomeSkipped\*',

            // file paths
            __DIR__ . '/Fixture/AlwaysSkippedPath',
            '*\PathSkippedWithMask\*',
            __DIR__ . '/Fixture/SomeSkippedPath',
            __DIR__ . '/Fixture/SomeSkippedPathToFile/any.txt',

            // elements
            FifthElement::class,

            // classes only in specific paths
            AnotherClassToSkip::class => ['Fixture/someFile', '*/someDirectory/*'],
        ]);

        $this->skipper = $this->make(Skipper::class);
    }

    protected function tearDown(): void
    {
        // cleanup configuration
        SimpleParameterProvider::setParameter(Option::SKIP, []);
    }

    #[DataProvider('provideDataShouldSkipFilePath')]
    public function testSkipFilePath(string $filePath, bool $expectedSkip): void
    {
        $filePathResultSkip = $this->skipper->shouldSkipFilePath($filePath);
        $this->assertSame($expectedSkip, $filePathResultSkip);
    }

    public function testResolveDeprecatedSkippedClasses(): void
    {
        $this->assertSame([], $this->skipper->resolveDeprecatedSkippedClasses());
    }

    /**
     * @return Iterator<string[]|bool[]>
     */
    public static function provideDataShouldSkipFilePath(): Iterator
    {
        yield [__DIR__ . '/Fixture/SomeRandom/file.txt', false];
        yield [__DIR__ . '/Fixture/SomeSkipped/any.txt', true];
        yield ['tests/Skipper/Skipper/Fixture/SomeSkippedPath/any.txt', true];
        yield ['tests/Skipper/Skipper/Fixture/SomeSkippedPathToFile/any.txt', true];
        yield [__DIR__ . '/Fixture/AlwaysSkippedPath/some_file.txt', true];
        yield [__DIR__ . '/Fixture/PathSkippedWithMask/another_file.txt', true];
    }

    #[DataProvider('provideRectorAndFile')]
    public function testSkipElementAndFilePath(object $rector, string $filePath, bool $expectedSkip): void
    {
        $resolvedSkip = $this->skipper->shouldSkipRectorAndFile($rector, $filePath);
        $this->assertSame($expectedSkip, $resolvedSkip);
    }

    public static function provideRectorAndFile(): Iterator
    {
        yield [new FifthElement(), __DIR__ . '/Fixture', true];

        yield [new AnotherClassToSkip(), __DIR__ . '/Fixture/someFile', true];
        yield [new AnotherClassToSkip(), __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];

        yield [new FifthElement(), __DIR__ . '/Fixture/someFile', true];
        yield [new FifthElement(), __DIR__ . '/Fixture/someDirectory/anotherFile.php', true];

        yield [new NotSkippedClass(), __DIR__ . '/Fixture/someFile', false];
        yield [new NotSkippedClass(), __DIR__ . '/Fixture/someOtherFile', false];
    }

    public function testCollectsOnlyMatchedSkips(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, [
            // path skip that will be matched
            __DIR__ . '/Fixture/SomeSkippedPath',

            // path skip that is never matched
            self::UNUSED_SKIP_MARKER,

            // class skip that will be matched
            FifthElement::class,
        ]);

        $this->skipper->shouldSkipFilePath('tests/Skipper/Skipper/Fixture/SomeSkippedPath/any.txt');
        $this->skipper->shouldSkipRectorAndFile(new FifthElement(), __DIR__ . '/Fixture/someFile');

        $usedSkips = $this->skipper->provideUsedSkips();

        // matched skips are collected as keys
        $this->assertArrayHasKey(FifthElement::class, $usedSkips);
        $this->assertNotEmpty(array_filter(
            array_keys($usedSkips),
            static fn (string $usedSkip): bool => str_ends_with($usedSkip, 'SomeSkippedPath')
        ));

        // unmatched skip is never collected
        $this->assertArrayNotHasKey(self::UNUSED_SKIP_MARKER, $usedSkips);
    }

    public function testCollectsMatchedPathNotRuleClassForRuleScopedSkip(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, [
            AnotherClassToSkip::class => [
                // matched path
                '*/someDirectory/*',
                // sibling path that never matches
                self::UNUSED_SKIP_MARKER,
            ],
        ]);

        $this->skipper->shouldSkipRectorAndFile(
            new AnotherClassToSkip(),
            __DIR__ . '/Fixture/someDirectory/anotherFile.php'
        );

        $usedSkips = $this->skipper->provideUsedSkips();

        // the specific matched path is collected nested under its rule class
        $this->assertArrayHasKey(AnotherClassToSkip::class, $usedSkips);
        $this->assertContains('*/someDirectory/*', $usedSkips[AnotherClassToSkip::class]);

        // the unmatched sibling path under the same rule is never collected
        $this->assertNotContains(self::UNUSED_SKIP_MARKER, $usedSkips[AnotherClassToSkip::class]);
    }
}
