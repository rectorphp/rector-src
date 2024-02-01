<?php

declare(strict_types=1);

namespace Rector\Tests\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Php\PhpVersionResolver\ProjectComposerJsonPhpVersionResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ProjectComposerJsonPhpVersionResolverTest extends AbstractLazyTestCase
{
    #[DataProvider('provideData')]
    public function test(string $composerJsonFilePath, int|null $expectedPhpVersion): void
    {
        $resolvePhpVersion = ProjectComposerJsonPhpVersionResolver::resolve($composerJsonFilePath);
        $this->assertSame($expectedPhpVersion, $resolvePhpVersion);
    }

    /**
     * @return Iterator<array<string|int>>
     */
    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/some_composer.json', 70300];
        yield [__DIR__ . '/Fixture/some_composer_with_platform.json', 70400];
        yield [__DIR__ . '/Fixture/no_php_definition_composer_json.json', null];
    }
}
