<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\TypeDeclaration\Guard\ParamTypeAddGuard;
use Rector\TypeDeclaration\NodeAnalyzer\CallerParamMatcher;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\ParamTypeByMethodCallTypeRectorTest
 */
final class ParamTypeByMethodCallTypeRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly CallerParamMatcher $callerParamMatcher,
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly ParamTypeAddGuard $paramTypeAddGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change param type based on passed method call type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeTypedService
{
    public function run(string $name)
    {
    }
}

final class UseDependency
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go($value)
    {
        $this->someTypedService->run($value);
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeTypedService
{
    public function run(string $name)
    {
    }
}

final class UseDependency
{
    public function __construct(
        private SomeTypedService $someTypedService
    ) {
    }

    public function go(string $value)
    {
        $this->someTypedService->run($value);
    }
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if ($this->shouldSkipClassMethod($classMethod)) {
                continue;
            }

            /** @var array<StaticCall|MethodCall|FuncCall> $callers */
            $callers = $this->betterNodeFinder->findInstancesOf(
                $classMethod,
                [StaticCall::class, MethodCall::class, FuncCall::class]
            );

            foreach ($classMethod->params as $param) {
                if ($this->shouldSkipParam($param, $classMethod)) {
                    continue;
                }

                foreach ($callers as $caller) {
                    $paramType = $this->callerParamMatcher->matchCallParamType($caller, $param, $scope);
                    if ($paramType === null) {
                        continue;
                    }

                    $this->mirrorParamType($param, $paramType);
                    $hasChanged = true;
                }
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function shouldSkipClassMethod(ClassMethod $classMethod): bool
    {
        if ($classMethod->params === []) {
            return true;
        }

        return $this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($classMethod);
    }

    private function mirrorParamType(
        Param $decoratedParam,
        Identifier | Name | NullableType | UnionType | ComplexType $paramType
    ): void {
        // mimic type
        $newParamType = $paramType;

        $this->traverseNodesWithCallable(
            $newParamType,
            static function (Node $node) {
                // original node has to removed to avoid tokens crashing from origin positions
                $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
                return null;
            }
        );

        $decoratedParam->type = $newParamType;
    }

    private function shouldSkipParam(Param $param, ClassMethod $classMethod): bool
    {
        if (! $this->paramTypeAddGuard->isLegal($param, $classMethod)) {
            return true;
        }

        if ($param->variadic) {
            return true;
        }

        // already has type, skip
        return $param->type !== null;
    }
}
