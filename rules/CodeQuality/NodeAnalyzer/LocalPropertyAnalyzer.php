<?php

declare(strict_types=1);

namespace Rector\CodeQuality\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\CodeQuality\TypeResolver\ArrayDimFetchTypeResolver;
use Rector\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;

final readonly class LocalPropertyAnalyzer
{
    /**
     * @var string
     */
    private const LARAVEL_COLLECTION_CLASS = 'Illuminate\Support\Collection';

    public function __construct(
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private NodeNameResolver $nodeNameResolver,
        private ArrayDimFetchTypeResolver $arrayDimFetchTypeResolver,
        private NodeTypeResolver $nodeTypeResolver,
        private PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private TypeFactory $typeFactory,
    ) {
    }

    /**
     * @return array<string, Type>
     */
    public function resolveFetchedPropertiesToTypesFromClass(Class_ $class): array
    {
        $fetchedLocalPropertyNameToTypes = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($class->getMethods(), function (Node $node) use (
            &$fetchedLocalPropertyNameToTypes
        ): ?int {
            if ($this->shouldSkip($node)) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($node instanceof Assign && ($node->var instanceof PropertyFetch || $node->var instanceof ArrayDimFetch)) {
                $propertyFetch = $node->var;

                $propertyName = $this->resolvePropertyName(
                    $propertyFetch instanceof ArrayDimFetch ? $propertyFetch->var : $propertyFetch
                );

                if ($propertyName === null) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($propertyFetch instanceof ArrayDimFetch) {
                    $fetchedLocalPropertyNameToTypes[$propertyName][] = $this->arrayDimFetchTypeResolver->resolve(
                        $propertyFetch,
                        $node
                    );
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                $fetchedLocalPropertyNameToTypes[$propertyName][] = $this->nodeTypeResolver->getType($node->expr);
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            $propertyName = $this->resolvePropertyName($node);
            if ($propertyName === null) {
                return null;
            }

            $fetchedLocalPropertyNameToTypes[$propertyName][] = new MixedType();

            return null;
        });

        return $this->normalizeToSingleType($fetchedLocalPropertyNameToTypes);
    }

    private function shouldSkip(Node $node): bool
    {
        // skip anonymous classes and inner function
        if ($node instanceof Class_ || $node instanceof Function_) {
            return true;
        }

        // skip closure call
        if ($node instanceof MethodCall && $node->var instanceof Closure) {
            return true;
        }

        if ($node instanceof StaticCall) {
            return $this->nodeNameResolver->isName($node->class, self::LARAVEL_COLLECTION_CLASS);
        }

        return false;
    }

    private function resolvePropertyName(Node $node): string|null
    {
        if (! $node instanceof PropertyFetch) {
            return null;
        }

        if (! $this->propertyFetchAnalyzer->isLocalPropertyFetch($node)) {
            return null;
        }

        if ($this->shouldSkipPropertyFetch($node)) {
            return null;
        }

        return $this->nodeNameResolver->getName($node->name);
    }

    private function shouldSkipPropertyFetch(PropertyFetch $propertyFetch): bool
    {
        if ($this->isPartOfClosureBind($propertyFetch)) {
            return true;
        }

        return $propertyFetch->name instanceof Variable;
    }

    /**
     * @param array<string, Type[]> $propertyNameToTypes
     * @return array<string, Type>
     */
    private function normalizeToSingleType(array $propertyNameToTypes): array
    {
        // normalize types to union
        $propertyNameToType = [];
        foreach ($propertyNameToTypes as $name => $types) {
            $propertyNameToType[$name] = $this->typeFactory->createMixedPassedOrUnionType($types);
        }

        return $propertyNameToType;
    }

    /**
     * Local property is actually not local one, but belongs to passed object
     * See https://ocramius.github.io/blog/accessing-private-php-class-members-without-reflection/
     */
    private function isPartOfClosureBind(PropertyFetch $propertyFetch): bool
    {
        $scope = $propertyFetch->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        return $scope->isInClosureBind();
    }
}
