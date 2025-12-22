<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Reflection\ClassReflection;
use Rector\Enum\ObjectReference;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\MethodName;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\RemoveParentDelegatingConstructorRectorTest
 */
final class RemoveParentDelegatingConstructorRector extends AbstractRector
{
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

        if ($node->stmts === null || count($node->stmts) !== 1) {
            return null;
        }

        if (! $this->hasParentClassWithConstructor($node)) {
            return null;
        }

        $soleStmt = $node->stmts[0];
        $parentCallArgs = $this->matchParentConstructorCallArgs($soleStmt);
        if ($parentCallArgs === null) {
            return null;
        }

        $constructorParams = $node->getParams();
        if (count($constructorParams) !== count($parentCallArgs)) {
            return null;
        }

        // match passed names in the same order
        $paramNames = [];
        foreach ($constructorParams as $constructorParam) {
            $paramNames[] = $this->getName($constructorParam->var);
        }

        $argNames = [];
        foreach ($parentCallArgs as $parentCallArg) {
            $argValue = $parentCallArg->value;
            if (! $argValue instanceof Variable) {
                return null;
            }

            $argNames[] = $this->getName($argValue);
        }

        if ($paramNames !== $argNames) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }

    private function hasParentClassWithConstructor(ClassMethod $classMethod): bool
    {
        $scope = ScopeFetcher::fetch($classMethod);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $parentClassReflection = $classReflection->getParentClass();
        if (! $parentClassReflection instanceof ClassReflection) {
            return false;
        }

        return $parentClassReflection->hasConstructor();
    }

    /**
     * Looking for parent::__construct()
     *
     * @return Arg[]|null
     */
    private function matchParentConstructorCallArgs(Stmt $stmt): ?array
    {
        if (! $stmt instanceof Expression) {
            return null;
        }

        if (! $stmt->expr instanceof StaticCall) {
            return null;
        }

        $staticCall = $stmt->expr;
        if (! $this->isName($staticCall->class, ObjectReference::PARENT)) {
            return null;
        }

        if (! $this->isName($staticCall->name, MethodName::CONSTRUCT)) {
            return null;
        }

        return $staticCall->getArgs();
    }
}
