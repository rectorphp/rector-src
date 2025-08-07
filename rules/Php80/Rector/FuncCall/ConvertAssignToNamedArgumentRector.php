<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitor;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParameterReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use Rector\DeadCode\NodeAnalyzer\ExprUsedInNodeAnalyzer;
use Rector\NodeAnalyzer\ArgsAnalyzer;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php80\Rector\FuncCall\ConvertAssignToNamedArgumentRector\ConvertAssignToNamedArgumentRectorTest
 */
final class ConvertAssignToNamedArgumentRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ArgsAnalyzer $argsAnalyzer,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ExprUsedInNodeAnalyzer $exprUsedInNodeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert assignments in function and method calls to named arguments',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
in_array('foo', $array, $strict = true);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
in_array('foo', $array, strict: true);
CODE_SAMPLE
                ),
            ]
        );
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NAMED_ARGUMENTS;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class, Namespace_::class, FileWithoutNamespace::class];
    }

    /**
     * @param ClassMethod|Function_|Namespace_|FileWithoutNamespace $node
     */
    public function refactor(Node $node): ?Node
    {
        $stmts = $node->stmts;

        if ($stmts === null || $stmts === []) {
            return null;
        }

        $hasChanged = false;
        $stmtIndex = 0;

        $this->traverseNodesWithCallable($stmts, function (Node $subNode) use (
            &$hasChanged,
            &$stmtIndex,
            $stmts
        ): ?int {
            if ($subNode instanceof Stmt) {
                ++$stmtIndex;
                return null;
            }

            if (! $subNode instanceof FuncCall && ! $subNode instanceof MethodCall && ! $subNode instanceof StaticCall) {
                return null;
            }

            if ($subNode->isFirstClassCallable()) {
                return null;
            }

            $hasChanged |= $this->refactorCall($subNode, array_slice($stmts, $stmtIndex));

            return null;
        });

        return $hasChanged ? $node : null;
    }

    /**
     * @param Stmt[] $stmts
     */
    private function refactorCall(FuncCall|MethodCall|StaticCall $call, array $stmts): bool
    {
        $args = $call->getArgs();
        $hasChanged = false;

        if ($this->argsAnalyzer->hasNamedArg($args)) {
            return false;
        }

        foreach ($args as $position => $arg) {
            if (! $arg->value instanceof Assign) {
                continue;
            }

            $assign = $arg->value;

            if (! $assign->var instanceof Variable) {
                continue;
            }

            $variable = $this->getName($assign->var);

            if ($variable === null) {
                continue;
            }

            if ($this->isVariableUsed($assign->var, $stmts)) {
                continue;
            }

            $parameter = $this->getParameterAtPosition($call, $position);

            if ($parameter === null || $parameter->isVariadic()) {
                continue;
            }

            if ($variable !== $parameter->getName()) {
                continue;
            }

            $arg->value = $assign->expr;
            $arg->name = new Identifier($variable);
            $hasChanged = true;
        }

        return $hasChanged;
    }

    /**
     * @param Node[]|Node $nodes
     */
    private function isVariableUsed(Variable $variable, array|Node $nodes): bool
    {
        $isUsed = false;
        $this->traverseNodesWithCallable($nodes, function (Node $subNode) use ($variable, &$isUsed): ?int {
            if ($subNode instanceof Assign && $subNode->var instanceof Variable) {
                if ($this->getName($subNode->var) !== $this->getName($variable)) {
                    return null;
                }

                if (! $this->isVariableUsed($variable, $subNode->expr)) {
                    return NodeVisitor::DONT_TRAVERSE_CHILDREN;
                }

                $isUsed = true;

                return NodeVisitor::STOP_TRAVERSAL;
            }

            if ($this->exprUsedInNodeAnalyzer->isUsed($subNode, $variable)) {
                $isUsed = true;

                return NodeVisitor::STOP_TRAVERSAL;
            }

            return null;
        });

        return $isUsed;
    }

    private function getParameterAtPosition(FuncCall|MethodCall|StaticCall $node, int $position): ?ParameterReflection
    {
        $reflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);

        if (! $reflection instanceof FunctionReflection && ! $reflection instanceof MethodReflection) {
            return null;
        }

        $parametersAcceptor = ParametersAcceptorSelector::combineAcceptors($reflection->getVariants());

        return $parametersAcceptor->getParameters()[$position] ?? null;
    }
}
