<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\NodeManipulator\ClassMethodManipulator;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\DeadCode\NodeManipulator\ControllerClassMethodManipulator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\RemoveEmptyClassMethodRectorTest
 */
final class RemoveEmptyClassMethodRector extends AbstractRector
{
    public function __construct(
        private ClassMethodManipulator $classMethodManipulator,
        private ControllerClassMethodManipulator $controllerClassMethodManipulator
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove empty class methods not required by parents',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class OrphanClass
{
    public function __construct()
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class OrphanClass
{
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $classLike = $node->getAttribute(AttributeKey::CLASS_NODE);
        if (! $classLike instanceof Class_) {
            return null;
        }

        if ($node->stmts !== null && $node->stmts !== []) {
            return null;
        }

        if ($node->isAbstract()) {
            return null;
        }

        if ($node->isFinal() && ! $classLike->isFinal()) {
            return null;
        }

        if ($this->shouldSkipNonFinalNonPrivateClassMethod($classLike, $node)) {
            return null;
        }

        if ($this->shouldSkipClassMethod($node)) {
            return null;
        }

        $this->removeNode($node);

        return $node;
    }

    private function shouldSkipNonFinalNonPrivateClassMethod(Class_ $class, ClassMethod $classMethod): bool
    {
        if ($class->isFinal()) {
            return false;
        }

        if ($classMethod->isMagic()) {
            return false;
        }

        if ($classMethod->isProtected()) {
            return true;
        }

        return $classMethod->isPublic();
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if ($this->classMethodManipulator->isNamedConstructor($classMethod)) {
            return true;
        }

        if ($this->classMethodManipulator->hasParentMethodOrInterfaceMethod($classMethod)) {
            return true;
        }

        if ($this->classMethodManipulator->isPropertyPromotion($classMethod)) {
            return true;
        }

        if ($this->controllerClassMethodManipulator->isControllerClassMethodWithBehaviorAnnotation($classMethod)) {
            return true;
        }

        if ($this->nodeNameResolver->isName($classMethod, MethodName::CONSTRUCT)) {
            $class = $classMethod->getAttribute(AttributeKey::CLASS_NODE);
            return $class instanceof Class_ && $class->extends instanceof FullyQualified;
        }

        return $this->nodeNameResolver->isName($classMethod, MethodName::INVOKE);
    }
}
