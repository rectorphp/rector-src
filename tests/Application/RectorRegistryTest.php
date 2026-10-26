<?php

declare(strict_types=1);

namespace Rector\Tests\Application;

use PhpParser\Node\Stmt\Class_;
use Rector\Application\RectorRegistry;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\PhpParser\NodeTraverser\Class_\OrderedClassRector;
use Rector\Tests\PhpParser\NodeTraverser\Class_\RuleUsingClassRector;
use Rector\Tests\PhpParser\NodeTraverser\ClassLike\RuleUsingClassLikeRector;
use Rector\Tests\PhpParser\NodeTraverser\Function_\RuleUsingFunctionRector;

final class RectorRegistryTest extends AbstractLazyTestCase
{
    private RectorRegistry $rectorRegistry;

    private RuleUsingFunctionRector $ruleUsingFunctionRector;

    private RuleUsingClassRector $ruleUsingClassRector;

    private RuleUsingClassLikeRector $ruleUsingClassLikeRector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rectorRegistry = $this->make(RectorRegistry::class);
        $this->rectorRegistry->refreshRectors([]);

        $this->ruleUsingFunctionRector = new RuleUsingFunctionRector();
        $this->ruleUsingClassRector = new RuleUsingClassRector();
        $this->ruleUsingClassLikeRector = new RuleUsingClassLikeRector();
    }

    public function testForNodeWhenNoRectorsAvailable(): void
    {
        $rectors = $this->rectorRegistry->forNode(new Class_('test'));

        $this->assertSame([], $rectors);
    }

    public function testForNodeWhenNoRectorsMatch(): void
    {
        $this->rectorRegistry->refreshRectors([$this->ruleUsingFunctionRector]);

        $rectors = $this->rectorRegistry->forNode(new Class_('test'));

        $this->assertSame([], $rectors);
    }

    public function testForNodeWhenSomeRectorsMatch(): void
    {
        $this->rectorRegistry->refreshRectors([
            $this->ruleUsingFunctionRector,
            $this->ruleUsingClassRector,
        ]);

        $rectors = $this->rectorRegistry->forNode(new Class_('test'));

        $this->assertEquals([$this->ruleUsingClassRector], $rectors);
    }

    public function testForNodeWhenAllRectorsMatch(): void
    {
        $this->rectorRegistry->refreshRectors([
            $this->ruleUsingClassRector,
            $this->ruleUsingClassLikeRector,
        ]);

        $rectors = $this->rectorRegistry->forNode(new Class_('test'));

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $rectors);
    }

    public function testForNodeSortsByPrecededRule(): void
    {
        $orderedClassRector = new OrderedClassRector();

        // registered before the rule it depends on
        $this->rectorRegistry->refreshRectors([
            $orderedClassRector,
            $this->ruleUsingClassRector,
        ]);

        $rectors = $this->rectorRegistry->forNode(new Class_('test'));

        $this->assertEquals([$this->ruleUsingClassRector, $orderedClassRector], $rectors);
    }
}
