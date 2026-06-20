<?php

declare(strict_types=1);

namespace Rector\Tests\Composer;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Rector\Composer\ComposerJsonPackageVersionResolver;

final class ComposerJsonPackageVersionResolverTest extends TestCase
{
    #[DataProvider('provideData')]
    public function test(string $composerJsonFilePath, bool $expected): void
    {
        $composerJsonPackageVersionResolver = new ComposerJsonPackageVersionResolver($composerJsonFilePath);

        $hasPackageMultiMajorVersions = $composerJsonPackageVersionResolver->hasPackageMultiMajorVersions(
            '/project/vendor/monolog/monolog/src/Logger.php'
        );

        $this->assertSame($expected, $hasPackageMultiMajorVersions);
    }

    /**
     * @return Iterator<(array<int, bool>|array<int, string>)>
     */
    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/ComposerJsonPackageVersionResolver/single_major.json', false];
        yield [__DIR__ . '/Fixture/ComposerJsonPackageVersionResolver/multi_major.json', true];
    }
}
