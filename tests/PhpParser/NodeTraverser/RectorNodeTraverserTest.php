<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser;

use PhpParser\Node\Stmt\Class_;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\PhpParser\NodeTraverser\Class_\RuleUsingClassRector;
use Rector\Tests\PhpParser\NodeTraverser\ClassLike\RuleUsingClassLikeRector;
use Rector\Tests\PhpParser\NodeTraverser\Function_\RuleUsingFunctionRector;

/**
 * @see \Rector\PhpParser\NodeTraverser\AbstractImmutableNodeTraverser
 */
final class RectorNodeTraverserTest extends AbstractLazyTestCase
{
    private RectorNodeTraverser $rectorNodeTraverser;

    private RuleUsingFunctionRector $ruleUsingFunctionRector;

    private RuleUsingClassRector $ruleUsingClassRector;

    private RuleUsingClassLikeRector $ruleUsingClassLikeRector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rectorNodeTraverser = $this->make(RectorNodeTraverser::class);
        $this->rectorNodeTraverser->refreshPhpRectors([]);

        $this->ruleUsingFunctionRector = new RuleUsingFunctionRector();
        $this->ruleUsingClassRector = new RuleUsingClassRector();
        $this->ruleUsingClassLikeRector = new RuleUsingClassLikeRector();
    }

    public function testGetVisitorsForNodeWhenNoVisitorsAvailable(): void
    {
        $class = new Class_('test');

        $visitors = $this->rectorNodeTraverser->getVisitorsForNode($class);

        $this->assertSame([], $visitors);
    }

    public function testGetVisitorsForNodeWhenNoVisitorsMatch(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->refreshPhpRectors([
            $this->ruleUsingFunctionRector,
        ]);

        $visitors = $this->rectorNodeTraverser->getVisitorsForNode($class);

        $this->assertSame([], $visitors);
    }

    public function testGetVisitorsForNodeWhenSomeVisitorsMatch(): void
    {
        $class = new Class_('test');

        $this->rectorNodeTraverser->refreshPhpRectors([
            new RuleUsingFunctionRector(),
            new RuleUsingClassRector()
        ]);

        $visitors = $this->rectorNodeTraverser->getVisitorsForNode($class);

        $this->assertEquals([$this->ruleUsingClassRector], $visitors);
    }

    public function testGetVisitorsForNodeWhenAllVisitorsMatch(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->refreshPhpRectors([
            $this->ruleUsingClassRector,
            $this->ruleUsingClassLikeRector
        ]);

        $visitorsForNode = $this->rectorNodeTraverser->getVisitorsForNode($class);

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $visitorsForNode);
    }

    public function testGetVisitorsForNodeUsesCachedValue(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->refreshPhpRectors([
            $this->ruleUsingClassRector,
            $this->ruleUsingClassLikeRector,
        ]);

        $visitors = $this->rectorNodeTraverser->getVisitorsForNode($class);

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $visitors);

        $this->rectorNodeTraverser->removeVisitor($this->ruleUsingClassRector);
        $visitors = $this->rectorNodeTraverser->getVisitorsForNode($class);

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $visitors);
    }
}
