<?php

declare(strict_types=1);

namespace Rector\PhpSpecToPHPUnit;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;

final class PhpSpecMockCollector
{
    /**
     * @var mixed[]
     */
    private array $mocks = [];

    /**
     * @var mixed[]
     */
    private array $mocksWithsTypes = [];

    /**
     * @var mixed[]
     */
    private array $propertyMocksByClass = [];

    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private NodeNameResolver $nodeNameResolver,
        private BetterNodeFinder $betterNodeFinder,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function resolveClassMocksFromParam(Class_ $class): array
    {
        $className = $this->nodeNameResolver->getName($class);

        if (isset($this->mocks[$className]) && $this->mocks[$className] !== []) {
            return $this->mocks[$className];
        }

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($class, function (Node $node): void {
            if (! $node instanceof ClassMethod) {
                return;
            }

            if (! $node->isPublic()) {
                return;
            }

            foreach ($node->params as $param) {
                $this->addMockFromParam($param);
            }
        });

        // set default value if none was found
        if (! isset($this->mocks[$className])) {
            $this->mocks[$className] = [];
        }

        return $this->mocks[$className];
    }

    public function isVariableMockInProperty(Variable $variable): bool
    {
        $scope = $variable->getAttribute(AttributeKey::SCOPE);

        $variableName = $this->nodeNameResolver->getName($variable);

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            throw new ShouldNotHappenException();
        }

        $className = $classReflection->getName();

        return in_array($variableName, $this->propertyMocksByClass[$className] ?? [], true);
    }

    public function getTypeForClassAndVariable(Class_ $class, string $variable): string
    {
        $className = $this->nodeNameResolver->getName($class);

        if (! isset($this->mocksWithsTypes[$className][$variable])) {
            throw new ShouldNotHappenException();
        }

        return $this->mocksWithsTypes[$className][$variable];
    }

    public function addPropertyMock(string $class, string $property): void
    {
        $this->propertyMocksByClass[$class][] = $property;
    }

    private function addMockFromParam(Param $param): void
    {
        $variable = $this->nodeNameResolver->getName($param->var);

        $scope = $param->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return;
        }

        $className = $scope->getClassReflection()?->getName();

        $classMethod = $this->betterNodeFinder->findParentType($param, ClassMethod::class);
        if (! $classMethod instanceof ClassMethod) {
            throw new ShouldNotHappenException();
        }

        $methodName = $this->nodeNameResolver->getName($classMethod);
        $this->mocks[$className][$variable][] = $methodName;

        if ($param->type === null) {
            throw new ShouldNotHappenException();
        }

        $paramType = (string) ($param->type->getAttribute(AttributeKey::ORIGINAL_NAME) ?: $param->type);
        $this->mocksWithsTypes[$className][$variable] = $paramType;
    }
}
