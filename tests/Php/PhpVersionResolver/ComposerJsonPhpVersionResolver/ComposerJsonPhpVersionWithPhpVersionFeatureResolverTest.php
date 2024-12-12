<?php

declare(strict_types=1);

namespace Rector\Tests\Php\PhpVersionResolver\ComposerJsonPhpVersionResolver;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Php\PhpVersionResolver\ComposerJsonPhpVersionResolver;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ComposerJsonPhpVersionWithPhpVersionFeatureResolverTest extends AbstractLazyTestCase
{
    public function test(): void
    {
        SimpleParameterProvider::setParameter(Option::PHP_VERSION_FEATURES, 80100);
        $resolvePhpVersion = ComposerJsonPhpVersionResolver::resolve(
            __DIR__ . '/Fixture/no_php_definition_composer_json.json'
        );

        $this->assertSame(80100, $resolvePhpVersion);
    }
}
