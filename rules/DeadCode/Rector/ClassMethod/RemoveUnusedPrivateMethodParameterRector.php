<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTagRemover;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\DeadCode\NodeCollector\UnusedParameterResolver;
use Rector\NodeCollector\NodeAnalyzer\ArrayCallableMethodMatcher;
use Rector\NodeCollector\ValueObject\ArrayCallable;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\RemoveUnusedPrivateMethodParameterRectorTest
 */
final class RemoveUnusedPrivateMethodParameterRector extends AbstractRector
{
    public function __construct(
        private readonly UnusedParameterResolver $unusedParameterResolver,
        private readonly PhpDocTagRemover $phpDocTagRemover,
        private readonly DocBlockUpdater $docBlockUpdater,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ArrayCallableMethodMatcher $arrayCallableMethodMatcher,
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach ($node->getMethods() as $classMethod) {
            if (! $classMethod->isPrivate()) {
                continue;
            }

            $unusedParameters = $this->unusedParameterResolver->resolve($classMethod);
            if ($unusedParameters === []) {
                continue;
            }

            // method used via reflection, eg: new ReflectionMethod(...[$this, 'method'])
            if ($this->isUsedByReflectionMethod($node, (string) $this->getName($classMethod))) {
                continue;
            }

            // early remove callers
            if (! $this->removeCallerArgs($node, $classMethod, $unusedParameters)) {
                continue;
            }

            $unusedParameterPositions = array_keys($unusedParameters);
            foreach (array_keys($classMethod->params) as $key) {
                if (! in_array($key, $unusedParameterPositions, true)) {
                    continue;
                }

                unset($classMethod->params[$key]);
            }

            // reset param keys
            $classMethod->params = array_values($classMethod->params);

            $this->clearPhpDocInfo($classMethod, $unusedParameters);

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function isUsedByReflectionMethod(Class_ $class, string $methodName): bool
    {
        $scope = ScopeFetcher::fetch($class);

        $reflectionMethodUsage = $this->betterNodeFinder->findFirst(
            $class,
            function (Node $subNode) use ($class, $scope, $methodName): bool {
                if (! $subNode instanceof New_ || ! $subNode->class instanceof Name) {
                    return false;
                }

                if (! $this->isName($subNode->class, 'ReflectionMethod')) {
                    return false;
                }

                if ($subNode->isFirstClassCallable()) {
                    return false;
                }

                foreach ($subNode->getArgs() as $arg) {
                    if (! $arg->value instanceof Array_) {
                        continue;
                    }

                    $arrayCallable = $this->arrayCallableMethodMatcher->match($arg->value, $scope, $methodName);
                    if ($arrayCallable instanceof ArrayCallable
                        && $this->isName($class, $arrayCallable->getClass())
                        && strcasecmp($arrayCallable->getMethod(), $methodName) === 0) {
                        return true;
                    }
                }

                return false;
            }
        );

        return $reflectionMethodUsage instanceof Node;
    }

    /**
     * @param Param[] $unusedParameters
     */
    private function removeCallerArgs(Class_ $class, ClassMethod $classMethod, array $unusedParameters): bool
    {
        $classMethods = $class->getMethods();
        if ($classMethods === []) {
            return false;
        }

        $methodName = $this->getName($classMethod);
        $keysArg = array_keys($unusedParameters);

        $classObjectType = new ObjectType((string) $this->getName($class));
        $callers = [];
        foreach ($classMethods as $classMethod) {
            /** @var MethodCall[]|StaticCall[] $callers */
            $callers = array_merge($callers, $this->resolveCallers($classMethod, $methodName, $classObjectType));
        }

        foreach ($callers as $caller) {
            if ($caller->isFirstClassCallable()) {
                return false;
            }

            foreach ($caller->getArgs() as $key => $arg) {
                if ($arg->unpack) {
                    return false;
                }

                if ($arg->name instanceof Identifier) {
                    if (isset($unusedParameters[$key]) && $this->isName(
                        $unusedParameters[$key],
                        (string) $this->getName($arg->name)
                    )) {
                        continue;
                    }

                    return false;
                }
            }
        }

        foreach ($callers as $caller) {
            $this->cleanupArgs($caller, $keysArg);
        }

        return true;
    }

    /**
     * @param int[] $keysArg
     */
    private function cleanupArgs(MethodCall|StaticCall $call, array $keysArg): void
    {
        $args = $call->getArgs();
        foreach (array_keys($args) as $key) {
            if (in_array($key, $keysArg, true)) {
                unset($args[$key]);
            }
        }

        // reset arg keys
        $call->args = array_values($args);
    }

    /**
     * @return MethodCall[]|StaticCall[]
     */
    private function resolveCallers(ClassMethod $classMethod, string $methodName, ObjectType $classObjectType): array
    {
        return $this->betterNodeFinder->find($classMethod, function (Node $subNode) use (
            $methodName,
            $classObjectType
        ): bool {
            if (! $subNode instanceof MethodCall && ! $subNode instanceof StaticCall) {
                return false;
            }

            $nodeToCheck = $subNode instanceof MethodCall
                ? $subNode->var
                : $subNode->class;

            if (! $this->isObjectType($nodeToCheck, $classObjectType)) {
                return false;
            }

            return $this->isName($subNode->name, $methodName);
        });
    }

    /**
     * @param Param[] $unusedParameters
     */
    private function clearPhpDocInfo(ClassMethod $classMethod, array $unusedParameters): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $hasChanged = false;

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

            $hasTagRemoved = $this->phpDocTagRemover->removeTagValueFromNode($phpDocInfo, $paramTagValueNode);
            if ($hasTagRemoved) {
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($classMethod);
        }
    }
}
