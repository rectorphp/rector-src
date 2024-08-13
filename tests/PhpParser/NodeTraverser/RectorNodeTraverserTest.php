<?php

declare(strict_types=1);

namespace Rector\Tests\PhpParser\NodeTraverser;

use PhpParser\Node\Stmt\Class_;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Tests\PhpParser\NodeTraverser\Class_\RuleUsingClassRector;
use Rector\Tests\PhpParser\NodeTraverser\ClassLike\RuleUsingClassLikeRector;
use Rector\Tests\PhpParser\NodeTraverser\Function_\RuleUsingFunctionRector;
use Rector\Util\Reflection\PrivatesAccessor;

final class RectorNodeTraverserTest extends AbstractLazyTestCase
{
    private RectorNodeTraverser $rectorNodeTraverser;

    private PrivatesAccessor $privatesAccessor;

    private RuleUsingFunctionRector $ruleUsingFunctionRector;

    private RuleUsingClassRector $ruleUsingClassRector;

    private RuleUsingClassLikeRector $ruleUsingClassLikeRector;

    protected function setUp(): void
    {
        $this->rectorNodeTraverser = $this->make(RectorNodeTraverser::class);
        $this->rectorNodeTraverser->refreshPhpRectors([]);

        $this->privatesAccessor = new PrivatesAccessor();

        $this->ruleUsingFunctionRector = new RuleUsingFunctionRector();
        $this->ruleUsingClassRector = new RuleUsingClassRector();
        $this->ruleUsingClassLikeRector = new RuleUsingClassLikeRector();
    }

    public function testGetVisitorsForNodeWhenNoVisitorsAvailable(): void
    {
        $class = new Class_('test');
        $visitors = $this->privatesAccessor->callPrivateMethod(
            $this->rectorNodeTraverser,
            'getVisitorsForNode',
            [$class]
        );

        $this->assertSame([], $visitors);
    }

    public function testGetVisitorsForNodeWhenNoVisitorsMatch(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingFunctionRector);
        $visitors = $this->privatesAccessor->callPrivateMethod(
            $this->rectorNodeTraverser,
            'getVisitorsForNode',
            [$class]
        );

        $this->assertSame([], $visitors);
    }

    public function testGetVisitorsForNodeWhenSomeVisitorsMatch(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingFunctionRector);
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingClassRector);

        $visitors = $this->privatesAccessor->callPrivateMethod(
            $this->rectorNodeTraverser,
            'getVisitorsForNode',
            [$class]
        );

        $this->assertEquals([$this->ruleUsingClassRector], $visitors);
    }

    public function testGetVisitorsForNodeWhenAllVisitorsMatch(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingClassRector);
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingClassLikeRector);

        $visitors = $this->privatesAccessor->callPrivateMethod(
            $this->rectorNodeTraverser,
            'getVisitorsForNode',
            [$class]
        );

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $visitors);
    }

    public function testGetVisitorsForNodeUsesCachedValue(): void
    {
        $class = new Class_('test');
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingClassRector);
        $this->rectorNodeTraverser->addVisitor($this->ruleUsingClassLikeRector);

        $visitors = $this->privatesAccessor->callPrivateMethod(
            $this->rectorNodeTraverser,
            'getVisitorsForNode',
            [$class]
        );

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $visitors);

        $this->rectorNodeTraverser->removeVisitor($this->ruleUsingClassRector);
        $visitors = $this->privatesAccessor->callPrivateMethod(
            $this->rectorNodeTraverser,
            'getVisitorsForNode',
            [$class]
        );

        $this->assertEquals([$this->ruleUsingClassRector, $this->ruleUsingClassLikeRector], $visitors);
    }
}
