<?php

declare(strict_types=1);

namespace Rector\Utils\Rector;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Reflection\ReflectionResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Utils\Tests\Rector\UseResolveFunctionLikeReflectionFromCallRector\UseResolveFunctionLikeReflectionFromCallRectorTest
 */
final class UseResolveFunctionLikeReflectionFromCallRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use resolveFunctionLikeReflectionFromCall() instead of duplicated ternary dispatch for method and static call reflections',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $methodReflection = $node instanceof MethodCall
            ? $this->reflectionResolver->resolveMethodReflectionFromMethodCall($node)
            : $this->reflectionResolver->resolveMethodReflectionFromStaticCall($node);
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->expr instanceof Ternary) {
            return null;
        }

        $comparedExprAndResolverMethodNames = $this->matchComparedExprAndResolverMethodNames($node->expr->cond);
        if ($comparedExprAndResolverMethodNames === null) {
            return null;
        }

        [$comparedExpr, $ifResolverMethodName, $elseResolverMethodName] = $comparedExprAndResolverMethodNames;

        if (! $node->expr->if instanceof MethodCall) {
            return null;
        }

        if (! $node->expr->else instanceof MethodCall) {
            return null;
        }

        if (! $this->isName($node->expr->if->name, $ifResolverMethodName)) {
            return null;
        }

        if (! $this->isName($node->expr->else->name, $elseResolverMethodName)) {
            return null;
        }

        if ($node->expr->if->isFirstClassCallable() || $node->expr->else->isFirstClassCallable()) {
            return null;
        }

        if (count($node->expr->if->getArgs()) !== 1 || count($node->expr->else->getArgs()) !== 1) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($node->expr->if->var, $node->expr->else->var)) {
            return null;
        }

        if (! $this->isObjectType($node->expr->if->var, new ObjectType(ReflectionResolver::class))) {
            return null;
        }

        $ifArg = $node->expr->if->getArgs()[0];
        if (! $ifArg instanceof Arg) {
            return null;
        }

        $elseArg = $node->expr->else->getArgs()[0];
        if (! $elseArg instanceof Arg) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($ifArg->value, $comparedExpr)) {
            return null;
        }

        if (! $this->nodeComparator->areNodesEqual($elseArg->value, $comparedExpr)) {
            return null;
        }

        $resolveFunctionLikeReflectionFromCall = clone $node->expr->if;
        $resolveFunctionLikeReflectionFromCall->name = new Identifier('resolveFunctionLikeReflectionFromCall');

        $node->expr = $resolveFunctionLikeReflectionFromCall;

        return $node;
    }

    /**
     * @return array{Expr, string, string}|null
     */
    private function matchComparedExprAndResolverMethodNames(Expr $expr): ?array
    {
        if (! $expr instanceof Instanceof_) {
            return null;
        }

        $className = $this->getName($expr->class);
        if ($className === MethodCall::class) {
            return [$expr->expr, 'resolveMethodReflectionFromMethodCall', 'resolveMethodReflectionFromStaticCall'];
        }

        if ($className === StaticCall::class) {
            return [$expr->expr, 'resolveMethodReflectionFromStaticCall', 'resolveMethodReflectionFromMethodCall'];
        }

        return null;
    }
}
