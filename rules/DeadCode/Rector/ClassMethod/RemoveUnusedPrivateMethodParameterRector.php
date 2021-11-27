<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\NodeCollector\UnusedParameterResolver;
use Rector\DeadCode\NodeManipulator\VariadicFunctionLikeDetector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\RemoveUnusedPrivateMethodParameterRectorTest
 */
final class RemoveUnusedPrivateMethodParameterRector extends AbstractRector
{
    public function __construct(
        private VariadicFunctionLikeDetector $variadicFunctionLikeDetector,
        private UnusedParameterResolver $unusedParameterResolver,
        private PhpDocTagRemover $phpDocTagRemover
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove unused parameter, if not required by interface or parent class',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    private function run($value, $value2)
    {
         $this->value = $value;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    private function run($value)
    {
         $this->value = $value;
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }

        $unusedParameters = $this->unusedParameterResolver->resolve($node);
        if ($unusedParameters === []) {
            return null;
        }

        $this->removeNodes($unusedParameters);
        $this->clearPhpDocInfo($node, $unusedParameters);
        $this->removeCallerArgs($node, $unusedParameters);

        return $node;
    }

    /**
     * @param Param[] $unusedParameters
     */
    private function removeCallerArgs(ClassMethod $classMethod, array $unusedParameters): void
    {
        $classLike = $this->betterNodeFinder->findParentType($classMethod, ClassLike::class);
        if (! $classLike instanceof ClassLike) {
            return;
        }

        $methods = $classLike->getMethods();
        if ($methods === []) {
            return;
        }

        $methodName = (string) $this->nodeNameResolver->getName($classMethod);
        $keysArg    = array_keys($unusedParameters);

        foreach ($methods as $method) {

            /** @var MethodCall[] $callers */
            $callers = $this->betterNodeFinder->find($method, function (Node $subNode) use ($methodName): bool {
                if (! $subNode instanceof MethodCall) {
                    return false;
                }

                if (! $subNode->var instanceof Variable) {
                    return false;
                }

                if (! $this->nodeNameResolver->isName($subNode->var, 'this')) {
                    return false;
                }

                return $this->nodeNameResolver->isName($subNode->name, $methodName);
            });

            foreach ($callers as $caller) {
                $args = $caller->getArgs();
                foreach ($args as $key => $arg) {
                    foreach ($keysArg as $keyArg) {
                        if ($key === $keyArg) {
                            unset($args[$key]);
                            continue 2;
                        }
                    }
                }

                $caller->args = $args;
            }
        }
    }

    private function shouldSkip(ClassMethod $classMethod): bool
    {
        if (! $classMethod->isPrivate()) {
            return true;
        }

        if ($classMethod->params === []) {
            return true;
        }

        return $this->variadicFunctionLikeDetector->isVariadic($classMethod);
    }

    /**
     * @param Param[] $unusedParameters
     */
    private function clearPhpDocInfo(ClassMethod $classMethod, array $unusedParameters): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);

        foreach ($unusedParameters as $unusedParameter) {
            $parameterName = $this->getName($unusedParameter->var);
            if ($parameterName === null) {
                continue;
            }

            $paramTagValueNode = $phpDocInfo->getParamTagValueByName($parameterName);
            if (! $paramTagValueNode instanceof ParamTagValueNode) {
                continue;
            }

            if ($paramTagValueNode->parameterName !== '$' . $parameterName) {
                continue;
            }

            $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $paramTagValueNode);
        }
    }
}
