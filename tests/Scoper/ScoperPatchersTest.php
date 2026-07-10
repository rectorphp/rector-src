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

    public function testKeepsPrefixedClassesInCodeSampleBlocksUnprefixed(): void
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
        return new RuleDefinition('Demo', [new ConfiguredCodeSample(<<<'CODE_SAMPLE'
<?php

namespace RectorPrefix202607;

class SomeClass
{
    public function run()
    {
    }
}
\class_alias('SomeClass', 'SomeClass', \false);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
<?php

namespace RectorPrefix202607;

use RectorPrefix202607\Webmozart\Assert\Assert;

class SomeClass
{
    public function run()
    {
        \RectorPrefix202607\Webmozart\Assert\Assert::allString($items);
    }
}
\class_alias('SomeClass', 'SomeClass', \false);
CODE_SAMPLE
        , [AssertClassName::WEBMOZART])]);
    }

    public function refactor(): void
    {
        $sample = <<<'CODE_SAMPLE'
<?php

namespace RectorPrefix202607;

\RectorPrefix202607\ShouldStayPrefixed::run();
\class_alias('SomeClass', 'SomeClass', \false);
CODE_SAMPLE;

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

        preg_match(
            '#public function getRuleDefinition\(\): RuleDefinition\s+\{\R(.*?)\R    \}\R\R    public function refactor\(\): void#s',
            (string) $content,
            $getRuleDefinitionMatches
        );
        $this->assertNotSame([], $getRuleDefinitionMatches);

        preg_match_all("#<<<'CODE_SAMPLE'\R(.*?)\RCODE_SAMPLE#s", $getRuleDefinitionMatches[1], $matches);
        $codeSampleContent = implode("\n", $matches[1]);

        $this->assertStringContainsString('use RectorPrefix202607\Webmozart\Assert\Assert;', (string) $content);
        $this->assertStringContainsString('use Webmozart\Assert\Assert;', $codeSampleContent);
        $this->assertStringContainsString(Assert::class . '::allString($items);', $codeSampleContent);
        $this->assertStringContainsString('\RectorPrefix202607\SomeVendor\Runtime::class;', (string) $content);
        $this->assertStringNotContainsString('namespace RectorPrefix202607;', $codeSampleContent);
        $this->assertStringNotContainsString('use RectorPrefix202607\Webmozart\Assert\Assert;', $codeSampleContent);
        $this->assertStringNotContainsString('\class_alias', $codeSampleContent);
        $this->assertStringContainsString('\RectorPrefix202607\ShouldStayPrefixed::run();', (string) $content);

        foreach ($matches[1] as $codeSample) {
            $this->assertStringEndsNotWith("\n", $codeSample);
        }
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
        return new RuleDefinition('Demo', [
            new CodeSample(<<<'CODE_SAMPLE'
<?php

namespace RectorPrefix202607;

RectorPrefix202607\SomeVendor\ValueObject::class;
\class_alias('SomeClass', 'SomeClass', \false);
CODE_SAMPLE
            , ''),
        ]);
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
        $this->assertStringNotContainsString('\class_alias', (string) $content);
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
