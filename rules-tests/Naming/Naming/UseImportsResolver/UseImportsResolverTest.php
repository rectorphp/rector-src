<?php

declare(strict_types=1);

namespace Rector\Tests\Naming\Naming\UseImportsResolver;

use Iterator;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Use_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Rector\Testing\TestingParser\TestingParser;
use Rector\Tests\Naming\Naming\UseImportsResolver\Source\FirstClass;
use Rector\Tests\Naming\Naming\UseImportsResolver\Source\SecondClass;

final class UseImportsResolverTest extends AbstractTestCase
{
    private UseImportsResolver $useImportsResolver;

    private TestingParser $testingParser;

    private BetterNodeFinder $betterNodeFinder;

    protected function setUp(): void
    {
        $this->boot();
        $this->useImportsResolver = $this->getService(UseImportsResolver::class);
        $this->testingParser = $this->getService(TestingParser::class);
        $this->betterNodeFinder = $this->getService(BetterNodeFinder::class);
    }

    #[DataProvider('provideData')]
    public function testUsesFromProperty(string $filePath): void
    {
        $nodes = $this->testingParser->parseFileToDecoratedNodes($filePath);

        $firstProperty = $this->betterNodeFinder->findFirstInstanceOf($nodes, Property::class);
        $this->assertInstanceOf(Property::class, $firstProperty);

        $resolvedUses = $this->useImportsResolver->resolveForNode($firstProperty);

        $stringUses = [];

        foreach ($resolvedUses as $resolvedUse) {
            foreach ($resolvedUse->uses as $useUse) {
                $stringUses[] = $resolvedUse instanceof Use_
                    ? $useUse->name->toString()
                    : $resolvedUse->prefix->toString() . '\\' . $useUse->name->toString();
            }
        }

        $this->assertContains(FirstClass::class, $stringUses);
        $this->assertContains(SecondClass::class, $stringUses);
    }

    public static function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture');
    }
}
