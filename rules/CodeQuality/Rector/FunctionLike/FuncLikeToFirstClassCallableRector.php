<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\VariadicPlaceholder;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector\FuncLikeToFirstClassCallableRectorTest
 */
final class FuncLikeToFirstClassCallableRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'converts function like to first class callable',
            [New CodeSample(
                <<<'CODE_SAMPLE'
function ($parameter) { return Call::to($parameter); }
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
Call::to(...);
CODE_SAMPLE
                ,
            )]
        );
    }

    public function getNodeTypes(): array
    {
        return [ArrowFunction::class, Closure::class];
    }

    /**
     * @param ArrowFunction|Closure $node
     */
    public function refactor(Node $node): null|StaticCall|MethodCall
    {
        $extractedMethodCall = $this->extractMethodCallFromFuncLike($node);

        if (! $extractedMethodCall instanceof MethodCall && ! $extractedMethodCall instanceof StaticCall) {
            return null;
        }

        if ($extractedMethodCall instanceof MethodCall) {
            return new MethodCall($extractedMethodCall->var, $extractedMethodCall->name, [new VariadicPlaceholder()]);
        }

        return new StaticCall($extractedMethodCall->class, $extractedMethodCall->name, [new VariadicPlaceholder()]);
    }

    private function extractMethodCallFromFuncLike(Closure|ArrowFunction $node): MethodCall|StaticCall|null
    {
        if ($node instanceof ArrowFunction) {
            if (
                ($node->expr instanceof MethodCall || $node->expr instanceof StaticCall) &&
                ! $node->expr->isFirstClassCallable() &&
                $this->sameParamsForArgs($node->getParams(), $node->expr->getArgs()) &&
                $this->isNonDependantMethod($node->expr, $node->getParams())
            ) {
                return $node->expr;
            }

            return null;
        }

        if (count($node->stmts) != 1 || ! $node->getStmts()[0] instanceof Return_) {
            return null;
        }

        $callLike = $node->getStmts()[0]
            ->expr;

        if (! $callLike instanceof MethodCall && ! $callLike instanceof StaticCall) {
            return null;
        }

        if (
            ! $callLike->isFirstClassCallable() &&
            $this->sameParamsForArgs($node->getParams(), $callLike->getArgs()) &&
            $this->isNonDependantMethod($callLike, $node->getParams())) {
            return $callLike;
        }

        return null;
    }

    /**
     * @param Node\Param[] $params
     * @param Node\Arg[] $args
     */
    private function sameParamsForArgs(array $params, array $args): bool
    {
        Assert::allIsInstanceOf($args, Arg::class);
        Assert::allIsInstanceOf($params, Param::class);

        if (count($args) > count($params)) {
            return false;
        }

        if (count($args) === 1 && $args[0]->unpack) {
            return $params[0]->variadic;
        }

        foreach ($args as $key => $arg) {
            if (! $this->nodeComparator->areNodesEqual($arg->value, $params[$key]->var)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Param[] $params
     */
    private function isNonDependantMethod(StaticCall|MethodCall $expr, array $params): bool
    {
        Assert::allIsInstanceOf($params, Param::class);

        if ($expr instanceof StaticCall) {
            return true;
        }

        foreach ($params as $param) {
            if ($this->nodeComparator->areNodesEqual($param->var, $expr->var)) {
                return false;
            }
        }

        return true;
    }
}
