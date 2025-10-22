<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\FunctionLike;

use PHPStan\Type\CallableType;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\VariadicPlaceholder;
use PhpParser\NodeVisitor;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ResolvedFunctionVariantWithOriginal;
use Rector\NodeTypeResolver\PHPStan\ParametersAcceptorSelectorVariantsWrapper;
use Rector\PhpParser\AstResolver;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector\FunctionLikeToFirstClassCallableRectorTest
 */
final class FunctionLikeToFirstClassCallableRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const HAS_CALLBACK_SIGNATURE_MULTI_PARAMS = 'has_callback_signature_multi_params';

    public function __construct(
        private readonly AstResolver $astResolver,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

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
        return [
            MethodCall::class,
            FuncCall::class,
            StaticCall::class,
            New_::class,
            ArrowFunction::class,
            Closure::class,
        ];
    }

    /**
     * @param MethodCall|FuncCall|StaticCall|New_|ArrowFunction|Closure $node
     */
    public function refactor(Node $node): null|CallLike
    {
        if ($node instanceof CallLike) {
            if ($node->isFirstClassCallable()) {
                return null;
            }

            $args = $node->getArgs();
            foreach ($args as $key => $arg) {
                if ($arg->value instanceof Closure || $arg->value instanceof ArrowFunction) {
                    // verify caller signature
                    $methodReflection = $this->reflectionResolver->resolveFunctionLikeReflectionFromCall($node);

                    if ($methodReflection === null) {
                        return null;
                    }

                    $reflection = ParametersAcceptorSelectorVariantsWrapper::select(
                        $methodReflection,
                        $node,
                        ScopeFetcher::fetch($node)
                    );

                    if ($reflection instanceof ResolvedFunctionVariantWithOriginal) {
                        return null;
                    }

                    foreach ($reflection->getParameters() as $index => $parameterReflection) {
                        if ($index === $key
                            && $parameterReflection->getType() instanceof CallableType
                            && count($parameterReflection->getType()->getParameters()) > 1
                        ) {
                            $args[$key]->value->setAttribute(self::HAS_CALLBACK_SIGNATURE_MULTI_PARAMS, true);
                            return null;
                        }
                    }
                }
            }

            return null;
        }

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

        if ($this->isUsingThisInNonObjectContext($callLike, $scope)) {
            return true;
        }

        if ($node->getAttribute(self::HAS_CALLBACK_SIGNATURE_MULTI_PARAMS) === true) {
            return true;
        }

        $functionLike = $this->astResolver->resolveClassMethodOrFunctionFromCall($callLike);
        if (! $functionLike instanceof FunctionLike) {
            return false;
        }

        return count($functionLike->getParams()) > 1;
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

        // dynamic name? skip
        if ($callLike->name instanceof Expr) {
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

            if ($arg->value instanceof Variable) {
                foreach ($params as $param) {
                    if ($param->var instanceof Variable && $param->variadic && ! $arg->unpack) {
                        return true;
                    }
                }
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
