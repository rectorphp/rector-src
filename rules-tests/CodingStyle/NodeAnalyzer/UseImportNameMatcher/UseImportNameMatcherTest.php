<?php

declare(strict_types=1);

namespace Rector\Tests\CodingStyle\NodeAnalyzer\UseImportNameMatcher;

use Iterator;
use PhpParser\Node\Stmt\Use_;
use PhpParser\ParserFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\CodingStyle\NodeAnalyzer\UseImportNameMatcher;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;
use Rector\Tests\CodingStyle\NodeAnalyzer\UseImportNameMatcher\Fixture\FixtureAnnotation\CustomAnnotation;

class UseImportNameMatcherTest extends AbstractLazyTestCase
{
    private TestingParser $testingParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testingParser = $this->make(TestingParser::class);
    }

    #[DataProvider('provideData')]
    public function testMatchNameWithUses(string $tag, string $expectedResolvedClass): void
    {
        $file = $this->testingParser->parseFilePathToFile(__DIR__ . '/Fixture/UseClass.php');

        $betterNodeFinder = $this->make(BetterNodeFinder::class);
        $useImportsResolver = $this->make(UseImportsResolver::class);
        $useImportNameMatcher = new UseImportNameMatcher($betterNodeFinder, $useImportsResolver);

        $parserFactory = new ParserFactory();
        $phpParser = $parserFactory->create(ParserFactory::PREFER_PHP7);

        $stmts = $phpParser->parse($file->getFileContent());
        if ($stmts === null) {
            $this->fail('No statements parsed');
        }

        $uses = $betterNodeFinder->findInstanceOf($stmts, Use_::class);
        foreach ($uses as $useKey => $use) {
            foreach ($use->uses as $useUseKey => $useUse) {
                $useUse->setAttribute(AttributeKey::ORIGINAL_NODE, $useUse);
                unset($use->uses[$useUseKey]);
                $use->uses[$useUseKey] = $useUse;
            }
            unset($uses[$useKey]);
            $uses[$useKey] = $use;
        }
        $resolvedName = $useImportNameMatcher->matchNameWithUses($tag, $uses);

        $this->assertEquals($expectedResolvedClass, $resolvedName);
    }

    public static function provideData(): Iterator
    {
        yield ['FixtureAnnotation\CustomAnnotation', CustomAnnotation::class];
        yield ['Test\CustomAnnotation', CustomAnnotation::class];
    }
}
