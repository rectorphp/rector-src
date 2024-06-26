<?php

declare(strict_types=1);

namespace Rector\Php81\Rector\Array_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\VariadicPlaceholder;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeCollector\NodeAnalyzer\ArrayCallableMethodMatcher;
use Rector\NodeCollector\ValueObject\ArrayCallable;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\FirstClassCallableRectorTest
 */
final class FirstClassCallableRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ArrayCallableMethodMatcher $arrayCallableMethodMatcher,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Upgrade array callable to first class callable', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $name = [$this, 'name'];
    }

    public function name()
    {
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $name = $this->name(...);
    }

    public function name()
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
        return [Property::class, ClassConst::class, Array_::class, FuncCall::class];
    }

    /**
     * @param Property|ClassConst|Array_|FuncCall $node
     */
    public function refactorWithScope(Node $node, Scope $scope): int|null|StaticCall|MethodCall|FuncCall
    {
        if ($node instanceof FuncCall) {
            if (! $node->name instanceof Name) {
                return null;
            }

            if ($node->isFirstClassCallable()) {
                return null;
            }

            $functionName = (string) $this->getName($node);

            try {
                $reflectionFunction = new ReflectionFunction($functionName);
            } catch (ReflectionException) {
                return null;
            }

            $callableArgs = [];

            foreach ($reflectionFunction->getParameters() as $reflectionParameter) {
                if ($reflectionParameter->getType() instanceof ReflectionNamedType && $reflectionParameter->getType()->getName() === 'callable') {
                    $callableArgs[] = $reflectionParameter->getPosition();
                }
            }

            foreach ($node->getArgs() as $key => $arg) {
                if (! in_array($key, $callableArgs, true)) {
                    continue;
                }

                if (! $arg->value instanceof String_) {
                    continue;
                }

                $node->args[$key] = new Arg(
                    new FuncCall(new Name($arg->value->value), [new VariadicPlaceholder()]),
                    name: $arg->name
                );
            }

            return $node;
        }

        if ($node instanceof Property || $node instanceof ClassConst) {
            return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        }

        $arrayCallable = $this->arrayCallableMethodMatcher->match($node, $scope);
        if (! $arrayCallable instanceof ArrayCallable) {
            return null;
        }

        $callerExpr = $arrayCallable->getCallerExpr();
        if (! $callerExpr instanceof Variable && ! $callerExpr instanceof PropertyFetch && ! $callerExpr instanceof ClassConstFetch) {
            return null;
        }

        $args = [new VariadicPlaceholder()];
        if ($callerExpr instanceof ClassConstFetch) {
            $type = $this->getType($callerExpr->class);
            if ($type instanceof FullyQualifiedObjectType && $this->isNonStaticOtherObject(
                $type,
                $arrayCallable,
                $scope
            )) {
                return null;
            }

            return new StaticCall($callerExpr->class, $arrayCallable->getMethod(), $args);
        }

        $methodName = $arrayCallable->getMethod();
        $methodCall = new MethodCall($callerExpr, $methodName, $args);
        $classReflection = $this->reflectionResolver->resolveClassReflectionSourceObject($methodCall);

        if ($classReflection instanceof ClassReflection && $classReflection->hasNativeMethod($methodName)) {
            $method = $classReflection->getNativeMethod($methodName);
            if (! $method->isPublic()) {
                return null;
            }
        }

        return $methodCall;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_81;
    }

    private function isNonStaticOtherObject(
        FullyQualifiedObjectType $fullyQualifiedObjectType,
        ArrayCallable $arrayCallable,
        Scope $scope
    ): bool {
        $classReflection = $scope->getClassReflection();
        if ($classReflection instanceof ClassReflection && $classReflection->getName() === $fullyQualifiedObjectType->getClassName()) {
            return false;
        }

        $arrayClassReflection = $this->reflectionProvider->getClass($arrayCallable->getClass());

        // we're unable to find it
        if (! $arrayClassReflection->hasMethod($arrayCallable->getMethod())) {
            return false;
        }

        $extendedMethodReflection = $arrayClassReflection->getMethod($arrayCallable->getMethod(), $scope);
        if (! $extendedMethodReflection->isStatic()) {
            return true;
        }

        return ! $extendedMethodReflection->isPublic();
    }
}
