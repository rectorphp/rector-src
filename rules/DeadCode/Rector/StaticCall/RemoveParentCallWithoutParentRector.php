<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\NodeManipulator\ClassMethodManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeCollector\ScopeResolver\ParentClassScopeResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector\RemoveParentCallWithoutParentRectorTest
 */
final class RemoveParentCallWithoutParentRector extends AbstractRector
{
    public function __construct(
        private ClassMethodManipulator $classMethodManipulator,
        private ParentClassScopeResolver $parentClassScopeResolver,
        private ClassAnalyzer $classAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove unused parent call with no parent class',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class OrphanClass
{
    public function __construct()
    {
         parent::__construct();
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class OrphanClass
{
    public function __construct()
    {
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        $classLike = $node->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            return null;
        }

        if (! $node->class instanceof Name) {
            return null;
        }

        if (! $this->isName($node->class, 'parent')) {
            return null;
        }

        $parentClassName = $this->parentClassScopeResolver->resolveParentClassName($node);
        if ($parentClassName === null) {
            $this->removeNode($node);
            return null;
        }

        $classMethod = $node->getAttribute(AttributeKey::METHOD_NODE);
        if (! $classMethod instanceof ClassMethod) {
            return null;
        }

        if ($this->classAnalyzer->isAnonymousClass($classLike)) {
            // currently the classMethodManipulator isn't able to find usages of anonymous classes
            return null;
        }

        $calledMethodName = $this->getName($node->name);
        if ($this->classMethodManipulator->hasParentMethodOrInterfaceMethod($classMethod, $calledMethodName)) {
            return null;
        }

        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof Expression) {
            return null;
        }

        $this->removeNode($node);

        return null;
    }
}
