<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node\Expr\Closure;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\Type;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeTypeAnalyzer\MethodCallParamTypeResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromStrictMethodCallPassRector\AddParamTypeFromStrictMethodCallPassRectorTest
 */
final class AddParamTypeFromStrictMethodCallPassRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly MethodCallParamTypeResolver $methodCallParamTypeResolver,
        private readonly StaticTypeMapper $staticTypeMapper,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change param type from strict type of passed-to method call',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run($value)
    {
        $this->resolve($value);
    }

    private function resolve(int $value)
    {
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run(int $value)
    {
        $this->resolve($value);
    }

    private function resolve(int $value)
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getParams() === []) {
            return null;
        }

        if ($node instanceof ClassMethod && $node->stmts === null) {
            return null;
        }

        $hasChanged = false;

        $usedVariables = $this->betterNodeFinder->findInstancesOfScoped((array) $node->stmts, Variable::class);

        foreach ($node->getParams() as $param) {
            // already known type
            if ($param->type instanceof Node) {
                continue;
            }

            /** @var string $paramName */
            $paramName = $this->getName($param->var);

            $paramVariables = array_filter(
                $usedVariables,
                fn (Variable $variable): bool => $this->isName($variable, $paramName)
            );

            if (count($paramVariables) >= 2) {
                // skip for now, as we look for sole use
                continue;
            }

            $methodCalls = $this->betterNodeFinder->findInstancesOfScoped((array) $node->stmts, MethodCall::class);
            foreach ($methodCalls as $methodCall) {
                if ($methodCall->isFirstClassCallable()) {
                    continue;
                }

                $typesByPosition = $this->methodCallParamTypeResolver->resolve($methodCall);

                $usedPosition = $this->matchParamMethodCallUsedPosition($methodCall, $paramName);
                if (! is_int($usedPosition)) {
                    continue;
                }

                $paramType = $typesByPosition[$usedPosition] ?? null;
                if (! $paramType instanceof Type) {
                    continue;
                }

                $paramTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($paramType, TypeKind::PARAM);
                if (! $paramTypeNode instanceof Node) {
                    continue;
                }

                $param->type = $paramTypeNode;
                $hasChanged = true;

                // go to next param
                continue 2;
            }
        }

        return null;
    }

    private function matchParamMethodCallUsedPosition(MethodCall $methodCall, string $paramName): int|null
    {
        foreach ($methodCall->getArgs() as $position => $arg) {
            if (! $arg->value instanceof Variable) {
                continue;
            }

            if (! $this->isName($arg->value, $paramName)) {
                continue;
            }

            return $position;
        }

        return null;
    }
}
