<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://3v4l.org/d4tBd
 *
 * @see \Rector\Tests\CodingStyle\Rector\ClassMethod\FuncGetArgsToVariadicParamRector\FuncGetArgsToVariadicParamRectorTest
 */
final class FuncGetArgsToVariadicParamRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Refactor func_get_args() in to a variadic param', [
            new CodeSample(
                <<<'CODE_SAMPLE'
function run()
{
    $args = \func_get_args();
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
function run(...$args)
{
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->params !== []) {
            return null;
        }

        /** @var Expression<Assign>|null $expression */
        $expression = $this->matchFuncGetArgsVariableAssign($node);
        if (! $expression instanceof Expression) {
            return null;
        }

        /** @var Assign $assign */
        $assign = $expression->expr;

        if ($assign->var instanceof Variable) {
            $variableName = $this->getName($assign->var);
            if ($variableName === null) {
                return null;
            }

            $this->removeNode($assign);
            return $this->applyVariadicParams($node, $variableName);
        }

        $assign->expr = new Variable('args');
        return $this->applyVariadicParams($node, 'args');
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::VARIADIC_PARAM;
    }

    private function applyVariadicParams(
        ClassMethod | Function_ | Closure $node,
        string $variableName
    ): ClassMethod | Function_ | Closure | null {
        $param = $this->createVariadicParam($variableName);
        $variableParam = $param->var;
        if ($variableParam instanceof Variable && $this->hasFunctionOrClosureInside($node, $variableParam)) {
            return null;
        }

        $node->params[] = $param;
        return $node;
    }

    private function hasFunctionOrClosureInside(
        ClassMethod | Function_ | Closure $functionLike,
        Variable $variable
    ): bool {
        if ($functionLike->stmts === null) {
            return false;
        }

        return (bool) $this->betterNodeFinder->findFirst($functionLike->stmts, function (Node $node) use (
            $variable
        ): bool {
            if (! $node instanceof Closure && ! $node instanceof Function_) {
                return false;
            }

            if ($node->params !== []) {
                return false;
            }

            $expression = $this->matchFuncGetArgsVariableAssign($node);
            if (! $expression instanceof Expression) {
                return false;
            }

            /** @var Assign $assign */
            $assign = $expression->expr;
            return $this->nodeComparator->areNodesEqual($assign->var, $variable);
        });
    }

    /**
     * @return Expression<Assign>|null
     */
    private function matchFuncGetArgsVariableAssign(ClassMethod | Function_ | Closure $functionLike): ?Expression
    {
        /** @var Expression[] $expressions */
        $expressions = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Expression::class);

        foreach ($expressions as $expression) {
            if (! $expression->expr instanceof Assign) {
                continue;
            }

            $assign = $expression->expr;
            if (! $assign->expr instanceof FuncCall) {
                continue;
            }

            if (! $this->isName($assign->expr, 'func_get_args')) {
                continue;
            }

            return $expression;
        }

        return null;
    }

    private function createVariadicParam(string $variableName): Param
    {
        $variable = new Variable($variableName);
        return new Param($variable, null, null, false, true);
    }
}
