<?php

declare(strict_types=1);

namespace Rector\Php80\Rector\ClassMethod;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\AstResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector\AddParamBasedOnParentClassMethodRectorTest
 */
final class AddParamBasedOnParentClassMethodRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly AstResolver $astResolver,
        private readonly BetterStandardPrinter $betterStandardPrinter,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::FATAL_ERROR_ON_INCOMPATIBLE_METHOD_SIGNATURE;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add missing parameter based on parent class method', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class A
{
    public function execute($foo)
    {
    }
}

class B extends A{
    public function execute()
    {
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class A
{
    public function execute($foo)
    {
    }
}

class B extends A{
    public function execute($foo)
    {
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->nodeNameResolver->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $parentMethodReflection = $this->parentClassMethodTypeOverrideGuard->getParentClassMethod($node);

        if (! $parentMethodReflection instanceof MethodReflection) {
            return null;
        }

        if ($parentMethodReflection->isPrivate()) {
            return null;
        }

        $currentClassReflection = $this->reflectionResolver->resolveClassReflection($node);
        $isPDO = $currentClassReflection instanceof ClassReflection && $currentClassReflection->isSubclassOf('PDO');

        // It relies on phpstorm stubs that define 2 kind of query method for both php 7.4 and php 8.0
        // @see https://github.com/JetBrains/phpstorm-stubs/blob/e2e898a29929d2f520fe95bdb2109d8fa895ba4a/PDO/PDO.php#L1096-L1126
        if ($isPDO && $parentMethodReflection->getName() === 'query') {
            return null;
        }

        $parentClassMethod = $this->astResolver->resolveClassMethodFromMethodReflection($parentMethodReflection);
        if (! $parentClassMethod instanceof ClassMethod) {
            return null;
        }

        $currentClassMethodParams = $node->getParams();
        $parentClassMethodParams = $parentClassMethod->getParams();

        $countCurrentClassMethodParams = count($currentClassMethodParams);
        $countParentClassMethodParams = count($parentClassMethodParams);

        if ($countCurrentClassMethodParams === $countParentClassMethodParams) {
            return null;
        }

        if ($countCurrentClassMethodParams < $countParentClassMethodParams) {
            return $this->processReplaceClassMethodParams(
                $node,
                $parentClassMethod,
                $currentClassMethodParams,
                $parentClassMethodParams
            );
        }

        return $this->processAddNullDefaultParam($node, $currentClassMethodParams, $parentClassMethodParams);
    }

    /**
     * @param Param[] $currentClassMethodParams
     * @param Param[] $parentClassMethodParams
     */
    private function processAddNullDefaultParam(
        ClassMethod $classMethod,
        array $currentClassMethodParams,
        array $parentClassMethodParams
    ): ?ClassMethod {
        $hasChanged = false;
        foreach ($currentClassMethodParams as $key => $currentClassMethodParam) {
            if (isset($parentClassMethodParams[$key])) {
                continue;
            }

            if ($currentClassMethodParam->default instanceof Expr) {
                continue;
            }

            if ($currentClassMethodParam->variadic) {
                continue;
            }

            $currentClassMethodParams[$key]->default = $this->nodeFactory->createNull();
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $classMethod;
    }

    /**
     * @param array<int, Param> $currentClassMethodParams
     * @param array<int, Param> $parentClassMethodParams
     */
    private function processReplaceClassMethodParams(
        ClassMethod $node,
        ClassMethod $parentClassMethod,
        array $currentClassMethodParams,
        array $parentClassMethodParams
    ): ?ClassMethod {
        $originalParams = $node->params;

        foreach ($parentClassMethodParams as $key => $parentClassMethodParam) {
            if (isset($currentClassMethodParams[$key])) {
                $currentParamName = $this->nodeNameResolver->getName($currentClassMethodParams[$key]);
                $collectParamNamesNextKey = $this->collectParamNamesNextKey($parentClassMethod, $key);

                if (in_array($currentParamName, $collectParamNamesNextKey, true)) {
                    $node->params = $originalParams;
                    return null;
                }

                continue;
            }

            $isUsedInStmts = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
                $node,
                function (Node $subNode) use ($parentClassMethodParam): bool {
                    if (! $subNode instanceof Variable) {
                        return false;
                    }

                    return $this->nodeComparator->areNodesEqual($subNode, $parentClassMethodParam->var);
                }
            );

            if ($isUsedInStmts) {
                $node->params = $originalParams;
                return null;
            }

            $paramDefault = $parentClassMethodParam->default;

            if ($paramDefault instanceof Expr) {
                $paramDefault = $this->nodeFactory->createReprintedNode($paramDefault);
            }

            $paramName = $this->nodeNameResolver->getName($parentClassMethodParam);
            $paramType = $this->resolveParamType($parentClassMethodParam);

            $node->params[$key] = new Param(
                new Variable($paramName),
                $paramDefault,
                $paramType,
                $parentClassMethodParam->byRef,
                $parentClassMethodParam->variadic,
                [],
                $parentClassMethodParam->flags
            );

            if ($parentClassMethodParam->attrGroups !== []) {
                $attrGroupsAsComment = $this->betterStandardPrinter->print($parentClassMethodParam->attrGroups);
                $node->params[$key]->setAttribute(AttributeKey::COMMENTS, [new Comment($attrGroupsAsComment)]);
            }
        }

        return $node;
    }

    private function resolveParamType(Param $param): null|Identifier|Name|ComplexType
    {
        if ($param->type === null) {
            return null;
        }

        return $this->nodeFactory->createReprintedNode($param->type);
    }

    /**
     * @return string[]
     */
    private function collectParamNamesNextKey(ClassMethod $classMethod, int $key): array
    {
        $paramNames = [];

        foreach ($classMethod->params as $paramKey => $param) {
            if ($paramKey > $key) {
                $paramNames[] = $this->nodeNameResolver->getName($param);
            }
        }

        return $paramNames;
    }
}
