<?php

declare(strict_types=1);

namespace Rector\Tests\Scoper;

use Webmozart\Assert\Assert;
use Closure;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

final class ScoperPatchersTest extends TestCase
{
    private static bool $isFinderAliased = false;

    public function testKeepsPrefixedClassesInGetRuleDefinitionUnprefixed(): void
    {
        $scoperConfig = $this->provideScoperConfig();

        $content = <<<'PHP'
<?php

namespace Rector\Assert\Rector\ClassMethod;

use RectorPrefix202607\Webmozart\Assert\Assert;

final class AddAssertArrayFromClassMethodDocblockRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        $metadata = 'RectorPrefix202607\Webmozart\Assert\Assert';

        new ConfiguredCodeSample(
            <<<'CODE_SAMPLE'
<?php

namespace RectorPrefix202607;

use RectorPrefix202607\Webmozart\Assert\Assert;
CODE_SAMPLE
            ,
            <<<'CODE_SAMPLE'
<?php

\RectorPrefix202607\Webmozart\Assert\Assert::allString($items);
CODE_SAMPLE
            ,
            []
        );

        new CodeSample(
            'RectorPrefix202607\SomeVendor\ValueObject::class',
            'new RectorPrefix202607\SomeVendor\ValueObject()'
        );
    }

    public function refactor(): void
    {
        \RectorPrefix202607\SomeVendor\Runtime::class;
    }
}
PHP;

        foreach ($scoperConfig['patchers'] as $patcher) {
            $content = $patcher->__invoke(
                __DIR__ . '/../../rules/Assert/Rector/ClassMethod/AddAssertArrayFromClassMethodDocblockRector.php',
                'RectorPrefix202607',
                $content
            );
        }

        $this->assertStringContainsString('use Webmozart\Assert\Assert;', (string) $content);
        $this->assertStringContainsString('use RectorPrefix202607\Webmozart\Assert\Assert;', (string) $content);
        $this->assertStringContainsString(Assert::class . '::allString($items);', (string) $content);
        $this->assertStringContainsString("'SomeVendor\ValueObject::class'", (string) $content);
        $this->assertStringContainsString("'new SomeVendor\ValueObject()'", (string) $content);
        $this->assertStringContainsString('$metadata = \'Webmozart\Assert\Assert\';', (string) $content);
        $this->assertStringContainsString('\RectorPrefix202607\SomeVendor\Runtime::class;', (string) $content);
        $this->assertStringNotContainsString('namespace RectorPrefix202607;', (string) $content);
    }

    public function testRemovesPrefixedNamespaceInGetRuleDefinitionWithWindowsLineEndings(): void
    {
        $scoperConfig = $this->provideScoperConfig();

        $content = str_replace(
            "\n",
            "\r\n",
            <<<'PHP'
<?php

final class SomeRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new CodeSample(
            '<?php
namespace RectorPrefix202607;

RectorPrefix202607\SomeVendor\ValueObject::class;'
        );
    }
}
PHP
        );

        foreach ($scoperConfig['patchers'] as $patcher) {
            $content = $patcher->__invoke(
                __DIR__ . '/../../rules/Some/Rector/SomeRector.php',
                'RectorPrefix202607',
                $content
            );
        }

        $this->assertStringNotContainsString('namespace RectorPrefix202607;', (string) $content);
        $this->assertStringContainsString('SomeVendor\ValueObject::class;', (string) $content);
    }

    /**
     * @return array{patchers: list<Closure(string, string, string): string>}
     */
    private function provideScoperConfig(): array
    {
        if (! self::$isFinderAliased) {
            class_alias(Finder::class, 'Isolated\Symfony\Component\Finder\Finder');
            self::$isFinderAliased = true;
        }

        return require __DIR__ . '/../../scoper.php';
    }
}
