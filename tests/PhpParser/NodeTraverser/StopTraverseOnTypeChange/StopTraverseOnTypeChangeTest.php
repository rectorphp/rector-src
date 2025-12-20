<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\NodeFinder;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;
use Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_\RuleChangingClassToTraitRector;
use Rector\Tests\PhpParser\NodeTraverser\StopTraverseOnTypeChange\Class_\RuleCheckingClassRector;

final class StopTraverseOnTypeChangeTest extends AbstractLazyTestCase
{
    private RectorNodeTraverser $rectorNodeTraverser;

    private TestingParser $testingParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rectorNodeTraverser = $this->make(RectorNodeTraverser::class);

        $this->rectorNodeTraverser->refreshPhpRectors([
            $this->make(RuleChangingClassToTraitRector::class),
            $this->make(RuleCheckingClassRector::class),
        ]);

        $this->testingParser = $this->make(TestingParser::class);
    }

    public function testGetVisitorsForNodeWhenNoVisitorsAvailable(): void
    {
        // must be cloned + Scope set to allow node replacement
        $nodes = $this->testingParser->parseFileToDecoratedNodes(__DIR__ . '/Fixture/SimpleClass.php');

        $changedNodes = $this->rectorNodeTraverser->traverse($nodes);

        $nodeFinder = new NodeFinder();
        $classes = $nodeFinder->findInstanceOf($changedNodes, Class_::class);
        $this->assertCount(0, $classes);

        $traits = $nodeFinder->findInstanceOf($changedNodes, Trait_::class);
        $this->assertCount(1, $traits);
    }
}
