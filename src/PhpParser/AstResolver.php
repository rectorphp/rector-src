<?php

declare(strict_types=1);

namespace Rector\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\PhpFunctionReflection;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Parser\RectorParser;
use Rector\Reflection\MethodReflectionResolver;
use Rector\StaticTypeMapper\Resolver\ClassNameFromObjectTypeResolver;
use Rector\ValueObject\MethodName;
use Throwable;

/**
 * The nodes provided by this resolver is for read-only analysis only!
 * They are not part of node tree processed by Rector, so any changes will not make effect in final printed file.
 */
final class AstResolver
{
    /**
     * Parsing files is very heavy performance, so this will help to leverage it
     * The value can be also null, when no statements could be parsed from the file.
     *
     * @var array<string, Stmt[]|null>
     */
    private array $parsedFileNodes = [];

    public function __construct(
        private readonly RectorParser $rectorParser,
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly MethodReflectionResolver $methodReflectionResolver,
        private readonly BetterNodeFinder $betterNodeFinder,
    ) {
    }

    /**
     * @api downgrade
     */
    public function resolveClassFromName(string $className): Class_ | Trait_ | Interface_ | Enum_ | null
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return null;
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        return $this->resolveClassFromClassReflection($classReflection);
    }

    public function resolveClassMethodFromMethodReflection(MethodReflection $methodReflection): ?ClassMethod
    {
        $classReflection = $methodReflection->getDeclaringClass();
        $fileName = $classReflection->getFileName();

        $nodes = $this->parseFileNameToDecoratedNodes($fileName);
        $classLikeName = $classReflection->getName();
        $methodName = $methodReflection->getName();

        /** @var ClassMethod|null $classMethod */
        $classMethod = null;
        $this->betterNodeFinder->findFirst(
            $nodes,
            function (Node $node) use ($classLikeName, $methodName, &$classMethod): bool {
                if (! $node instanceof ClassLike) {
                    return false;
                }

                if (! $this->nodeNameResolver->isName($node, $classLikeName)) {
                    return false;
                }

                $method = $node->getMethod($methodName);
                if ($method instanceof ClassMethod) {
                    $classMethod = $method;
                    return true;
                }

                return false;
            }
        );

        return $classMethod;
    }

    public function resolveClassMethodOrFunctionFromCall(
        FuncCall | StaticCall | MethodCall $call,
        Scope $scope
    ): ClassMethod | Function_ | null {
        if ($call instanceof FuncCall) {
            return $this->resolveFunctionFromFuncCall($call, $scope);
        }

        return $this->resolveClassMethodFromCall($call);
    }

    public function resolveFunctionFromFunctionReflection(FunctionReflection $functionReflection): ?Function_
    {
        if (! $functionReflection instanceof PhpFunctionReflection) {
            return null;
        }

        $fileName = $functionReflection->getFileName();
        $nodes = $this->parseFileNameToDecoratedNodes($fileName);

        $functionName = $functionReflection->getName();

        /** @var Function_|null $functionNode */
        $functionNode = $this->betterNodeFinder->findFirst(
            $nodes,
            function (Node $node) use ($functionName): bool {
                if (! $node instanceof Function_) {
                    return false;
                }

                return $this->nodeNameResolver->isName($node, $functionName);
            }
        );

        return $functionNode;
    }

    /**
     * @param class-string $className
     */
    public function resolveClassMethod(string $className, string $methodName): ?ClassMethod
    {
        $methodReflection = $this->methodReflectionResolver->resolveMethodReflection($className, $methodName, null);
        if (! $methodReflection instanceof MethodReflection) {
            return null;
        }

        $classMethod = $this->resolveClassMethodFromMethodReflection($methodReflection);

        if (! $classMethod instanceof ClassMethod) {
            return $this->locateClassMethodInTrait($methodName, $methodReflection);
        }

        return $classMethod;
    }

    public function resolveClassMethodFromCall(MethodCall | StaticCall | NullsafeMethodCall $call): ?ClassMethod
    {
        $callerStaticType = ($call instanceof MethodCall || $call instanceof NullsafeMethodCall)
            ? $this->nodeTypeResolver->getType($call->var)
            : $this->nodeTypeResolver->getType($call->class);

        $className = ClassNameFromObjectTypeResolver::resolve($callerStaticType);
        if ($className === null) {
            return null;
        }

        $methodName = $this->nodeNameResolver->getName($call->name);
        if ($methodName === null) {
            return null;
        }

        return $this->resolveClassMethod($className, $methodName);
    }

    public function resolveClassFromClassReflection(
        ClassReflection $classReflection
    ): Trait_ | Class_ | Interface_ | Enum_ | null {
        if ($classReflection->isBuiltin()) {
            return null;
        }

        $fileName = $classReflection->getFileName();
        $stmts = $this->parseFileNameToDecoratedNodes($fileName);
        $className = $classReflection->getName();

        /** @var Class_|Trait_|Interface_|Enum_|null $classLike */
        $classLike = $this->betterNodeFinder->findFirst(
            $stmts,
            function (Node $node) use ($className): bool {
                if (! $node instanceof ClassLike) {
                    return false;
                }

                return $this->nodeNameResolver->isName($node, $className);
            }
        );

        return $classLike;
    }

    /**
     * @return Trait_[]
     */
    public function parseClassReflectionTraits(ClassReflection $classReflection): array
    {
        /** @var ClassReflection[] $classLikes */
        $classLikes = $classReflection->getTraits(true);
        $traits = [];
        foreach ($classLikes as $classLike) {
            $fileName = $classLike->getFileName();
            $nodes = $this->parseFileNameToDecoratedNodes($fileName);
            $traitName = $classLike->getName();

            $traitNode = $this->betterNodeFinder->findFirst(
                $nodes,
                function (Node $node) use ($traitName): bool {
                    if (! $node instanceof Trait_) {
                        return false;
                    }

                    return $this->nodeNameResolver->isName($node, $traitName);
                }
            );

            if (! $traitNode instanceof Trait_) {
                continue;
            }

            $traits[] = $traitNode;
        }

        return $traits;
    }

    public function resolvePropertyFromPropertyReflection(
        PhpPropertyReflection $phpPropertyReflection
    ): Property | Param | null {
        $classReflection = $phpPropertyReflection->getDeclaringClass();

        $fileName = $classReflection->getFileName();
        $nodes = $this->parseFileNameToDecoratedNodes($fileName);
        if ($nodes === []) {
            return null;
        }

        $nativeReflectionProperty = $phpPropertyReflection->getNativeReflection();
        $desiredClassName = $classReflection->getName();
        $desiredPropertyName = $nativeReflectionProperty->getName();

        $propertyNode = null;
        $this->betterNodeFinder->findFirst(
            $nodes,
            function (Node $node) use ($desiredClassName, $desiredPropertyName, &$propertyNode): bool {
                if (! $node instanceof ClassLike) {
                    return false;
                }

                if (! $this->nodeNameResolver->isName($node, $desiredClassName)) {
                    return false;
                }

                $property = $node->getProperty($desiredPropertyName);
                if ($property instanceof Property) {
                    $propertyNode = $property;
                    return true;
                }

                return false;
            }
        );

        if ($propertyNode instanceof Property) {
            return $propertyNode;
        }

        // promoted property
        return $this->findPromotedPropertyByName($nodes, $desiredClassName, $desiredPropertyName);
    }

    /**
     * @return Stmt[]
     */
    public function parseFileNameToDecoratedNodes(?string $fileName): array
    {
        // probably native PHP → un-parseable
        if ($fileName === null) {
            return [];
        }

        if (isset($this->parsedFileNodes[$fileName])) {
            return $this->parsedFileNodes[$fileName];
        }

        try {
            $stmts = $this->rectorParser->parseFile($fileName);
        } catch (Throwable $throwable) {
            /**
             * phpstan.phar contains jetbrains/phpstorm-stubs which the code is not downgraded
             * that if read from lower php < 8.1 may cause crash
             *
             * @see https://github.com/rectorphp/rector/issues/8193 on php 8.0
             * @see https://github.com/rectorphp/rector/issues/8145 on php 7.4
             */
            if (str_contains($fileName, 'phpstan.phar')) {
                return [];
            }

            throw $throwable;
        }

        return $this->parsedFileNodes[$fileName] = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile(
            $fileName,
            $stmts
        );
    }

    private function locateClassMethodInTrait(string $methodName, MethodReflection $methodReflection): ?ClassMethod
    {
        $classReflection = $methodReflection->getDeclaringClass();
        $traits = $this->parseClassReflectionTraits($classReflection);

        /** @var ClassMethod|null $classMethod */
        $classMethod = $this->betterNodeFinder->findFirst(
            $traits,
            function (Node $node) use ($methodName): bool {
                if (! $node instanceof ClassMethod) {
                    return false;
                }

                return $this->nodeNameResolver->isName($node, $methodName);
            }
        );

        return $classMethod;
    }

    /**
     * @param Stmt[] $stmts
     */
    private function findPromotedPropertyByName(
        array $stmts,
        string $desiredClassName,
        string $desiredPropertyName
    ): ?Param {
        /** @var Param|null $paramNode */
        $paramNode = null;

        $this->betterNodeFinder->findFirst(
            $stmts,
            function (Node $node) use ($desiredClassName, $desiredPropertyName, &$paramNode): bool {
                if (! $node instanceof Class_) {
                    return false;
                }

                if (! $this->nodeNameResolver->isName($node, $desiredClassName)) {
                    return false;
                }

                $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);
                if (! $constructClassMethod instanceof ClassMethod) {
                    return false;
                }

                foreach ($constructClassMethod->getParams() as $param) {
                    if (! $param->isPromoted()) {
                        continue;
                    }

                    if ($this->nodeNameResolver->isName($param, $desiredPropertyName)) {
                        $paramNode = $param;
                        return true;
                    }
                }

                return false;
            }
        );

        return $paramNode;
    }

    private function resolveFunctionFromFuncCall(FuncCall $funcCall, Scope $scope): ?Function_
    {
        if ($funcCall->name instanceof Expr) {
            return null;
        }

        $functionName = new Name((string) $this->nodeNameResolver->getName($funcCall));
        if (! $this->reflectionProvider->hasFunction($functionName, $scope)) {
            return null;
        }

        $functionReflection = $this->reflectionProvider->getFunction($functionName, $scope);
        return $this->resolveFunctionFromFunctionReflection($functionReflection);
    }
}
