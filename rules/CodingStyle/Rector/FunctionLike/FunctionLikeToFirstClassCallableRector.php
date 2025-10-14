<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\VariadicPlaceholder;
use PhpParser\NodeVisitor;
use PHPStan\Analyser\Scope;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\FunctionLikeToFirstClassCallableRectorTest
 */
final class FunctionLikeToFirstClassCallableRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Converts arrow function and closures to first class callable',
            [new CodeSample(
                <<<'CODE_SAMPLE'
function ($parameter) {
    return Call::to($parameter);
}
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
    public function refactor(Node $node): null|CallLike
    {
        $callLike = $this->extractCallLike($node);

        if ($callLike === null) {
            return null;
        }

        if ($this->shouldSkip($node, $callLike, ScopeFetcher::fetch($node))) {
            return null;
        }

        $callLike->args = [new VariadicPlaceholder()];

        return $callLike;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FIRST_CLASS_CALLABLE_SYNTAX;
    }

    private function shouldSkip(
        ArrowFunction|Closure $node,
        FuncCall|MethodCall|StaticCall $callLike,
        Scope $scope
    ): bool {
        $params = $node->getParams();
        if ($callLike->isFirstClassCallable()) {
            return true;
        }

        $args = $callLike->getArgs();
        if ($this->isChainedCall($callLike)) {
            return true;
        }

        if ($this->isUsingNamedArgs($args)) {
            return true;
        }

        if ($this->isUsingByRef($params)) {
            return true;
        }

        if ($this->isNotUsingSameParamsForArgs($params, $args)) {
            return true;
        }

        if ($this->isDependantMethod($callLike, $params)) {
            return true;
        }

        return $this->isUsingThisInNonObjectContext($callLike, $scope);
    }

    private function extractCallLike(Closure|ArrowFunction $node): FuncCall|MethodCall|StaticCall|null
    {
        if ($node instanceof Closure) {
            if (count($node->stmts) !== 1 || ! $node->stmts[0] instanceof Return_) {
                return null;
            }

            $callLike = $node->stmts[0]->expr;
        } else {
            $callLike = $node->expr;
        }

        if (
            ! $callLike instanceof FuncCall
            && ! $callLike instanceof MethodCall
            && ! $callLike instanceof StaticCall
        ) {
            return null;
        }

        return $callLike;
    }

    /**
     * @param Param[] $params
     * @param Arg[] $args
     */
    private function isNotUsingSameParamsForArgs(array $params, array $args): bool
    {
        if (count($args) > count($params)) {
            return true;
        }

        if (count($args) === 1 && $args[0]->unpack) {
            return ! $params[0]->variadic;
        }

        foreach ($args as $key => $arg) {
            if (! $this->nodeComparator->areNodesEqual($arg->value, $params[$key]->var)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Param[] $params
     */
    private function isDependantMethod(StaticCall|MethodCall|FuncCall $expr, array $params): bool
    {
        if ($expr instanceof FuncCall) {
            return false;
        }

        $found = false;
        $parentNode = $expr instanceof MethodCall ? $expr->var : $expr->class;

        foreach ($params as $param) {
            $this->traverseNodesWithCallable($parentNode, function (Node $node) use ($param, &$found) {
                if ($this->nodeComparator->areNodesEqual($node, $param->var)) {
                    $found = true;
                    return NodeVisitor::STOP_TRAVERSAL;
                }
            });

            if ($found) {
                return true;
            }
        }

        return false;
    }

    private function isUsingThisInNonObjectContext(FuncCall|MethodCall|StaticCall $callLike, Scope $scope): bool
    {
        if (! $callLike instanceof MethodCall) {
            return false;
        }

        if (in_array('this', $scope->getDefinedVariables(), true)) {
            return false;
        }

        $found = false;

        $this->traverseNodesWithCallable($callLike, function (Node $node) use (&$found) {
            if ($this->isName($node, 'this')) {
                $found = true;
                return NodeVisitor::STOP_TRAVERSAL;
            }
        });

        return $found;
    }

    /**
     * @param Param[] $params
     */
    private function isUsingByRef(array $params): bool
    {
        foreach ($params as $param) {
            if ($param->byRef) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Arg[] $args
     */
    private function isUsingNamedArgs(array $args): bool
    {
        foreach ($args as $arg) {
            if ($arg->name instanceof Identifier) {
                return true;
            }
        }

        return false;
    }

    private function isChainedCall(FuncCall|MethodCall|StaticCall $callLike): bool
    {
        if (! $callLike instanceof MethodCall) {
            return false;
        }

        return $callLike->var instanceof CallLike;
    }
}
