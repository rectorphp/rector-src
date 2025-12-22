<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitor;
use PHPStan\Reflection\ClassReflection;
use Rector\Enum\ObjectReference;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\RemoveParentDelegatingConstructorRectorTest
 */
final class RemoveParentDelegatingConstructorRector extends AbstractRector
{
    public function __construct(
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly ValueResolver $valueResolver,
        private readonly ExprAnalyzer $exprAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove constructor that only delegates call to parent class with same values',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class Node
{
    public function __construct(array $attributes)
    {
    }
}

class SomeParent extends Node
{
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class Node
{
    public function __construct(array $attributes)
    {
    }
}

class SomeParent extends Node
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
    public function refactor(Node $node): ?int
    {
        if (! $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        if (count($node->stmts) !== 1) {
            return null;
        }

        $scope = ScopeFetcher::fetch($node);
        $classReflection = $scope->getClassReflection();
        if (! $classReflection->getParentClass() instanceof ClassReflection) {
            return null;
        }

        $parentClassReflection = $classReflection->getParentClass();
        if (! $parentClassReflection->hasConstructor()) {
            return null;
        }

        // $parentClassReflectionConstructor = $parentClassReflection->getConstructor();

        $soleStmt = $node->stmts[0];
        if (! $soleStmt instanceof Node\Stmt\Expression) {
            return null;
        }

        if (! $soleStmt->expr instanceof Node\Expr\StaticCall) {
            return null;
        }

        $staticCall = $soleStmt->expr;
        if (! $this->isName($staticCall->class, ObjectReference::PARENT)) {
            return null;
        }

        if (! $this->isName($staticCall->name, MethodName::CONSTRUCT)) {
            return null;
        }

        $constructorParams = $node->getParams();
        $parentCallArgs = $staticCall->getArgs();

        if (count($constructorParams) !== count($parentCallArgs)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
