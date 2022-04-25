<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\PHPStan\Type;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\UseUse;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedGenericObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;
use Rector\TypeDeclaration\Contract\PHPStan\TypeWithClassTypeSpecifierInterface;

final class ObjectTypeSpecifier
{
    /**
     * @param TypeWithClassTypeSpecifierInterface[] $typeWithClassTypeSpecifiers
     */
    public function __construct(
        private readonly ReflectionProvider $reflectionProvider,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly array $typeWithClassTypeSpecifiers
    ) {
    }

    public function narrowToFullyQualifiedOrAliasedObjectType(
        Node $node,
        ObjectType $objectType,
        Scope|null $scope
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> f461a04de4... add ObjectTypeSpecifier
    ): TypeWithClassName | NonExistingObjectType | UnionType | MixedType {
        $sameNamespacedFullyQualifiedObjectType = $this->matchSameNamespacedObjectType($node, $objectType);
        if ($sameNamespacedFullyQualifiedObjectType !== null) {
            return $sameNamespacedFullyQualifiedObjectType;
<<<<<<< HEAD
=======
    ): FullyQualifiedObjectType | AliasedObjectType | ShortenedObjectType | ShortenedGenericObjectType | StaticType | SelfObjectType | NonExistingObjectType | UnionType | MixedType {
        $sameNamespacedObjectType = $this->matchSameNamespacedObjectType($node, $objectType);
        if ($sameNamespacedObjectType !== null) {
            return $sameNamespacedObjectType;
>>>>>>> 7fd43e5501... narrow to FQN
=======
>>>>>>> f461a04de4... add ObjectTypeSpecifier
        }

        if ($scope instanceof Scope) {
            foreach ($this->typeWithClassTypeSpecifiers as $typeWithClassTypeSpecifier) {
                if ($typeWithClassTypeSpecifier->match($objectType, $scope)) {
                    return $typeWithClassTypeSpecifier->resolveObjectReferenceType($objectType, $scope);
                }
            }
        }

        $uses = $this->useImportsResolver->resolveForNode($node);
        if ($uses === []) {
            if (! $this->reflectionProvider->hasClass($objectType->getClassName())) {
                return new NonExistingObjectType($objectType->getClassName());
            }

            return new FullyQualifiedObjectType(
                $objectType->getClassName(),
                null,
                $objectType->getClassReflection()
            );
        }

        $aliasedObjectType = $this->matchAliasedObjectType($node, $objectType);
        if ($aliasedObjectType !== null) {
            return $aliasedObjectType;
        }

        $shortenedObjectType = $this->matchShortenedObjectType($node, $objectType);
        if ($shortenedObjectType !== null) {
            return $shortenedObjectType;
        }

        $className = ltrim($objectType->getClassName(), '\\');

        if ($this->reflectionProvider->hasClass($className)) {
            return new FullyQualifiedObjectType($className);
        }

        // invalid type
        return new NonExistingObjectType($className);
    }

    private function matchAliasedObjectType(Node $node, ObjectType $objectType): ?AliasedObjectType
    {
        $uses = $this->useImportsResolver->resolveForNode($node);
        if ($uses === []) {
            return null;
        }

        $className = $objectType->getClassName();

        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);

        foreach ($uses as $use) {
            foreach ($use->uses as $useUse) {
                if ($useUse->alias === null) {
                    continue;
                }

                $useName = $useUse->name->toString();
                $alias = $useUse->alias->toString();
                $fullyQualifiedName = $useUse->name->toString();

                $processAliasedObject = $this->processAliasedObject(
                    $alias,
                    $className,
                    $useName,
                    $parent,
                    $fullyQualifiedName
                );
                if ($processAliasedObject instanceof AliasedObjectType) {
                    return $processAliasedObject;
                }
            }
        }

        return null;
    }

    private function processAliasedObject(
        string $alias,
        string $className,
        string $useName,
        ?Node $parentNode,
        string $fullyQualifiedName
    ): ?AliasedObjectType {
        // A. is alias in use statement matching this class alias
        if ($alias === $className) {
            return new AliasedObjectType($alias, $fullyQualifiedName);
        }

        // B. is aliased classes matching the class name and parent node is MethodCall/StaticCall
        if ($useName === $className && ($parentNode instanceof MethodCall || $parentNode instanceof StaticCall)) {
            return new AliasedObjectType($useName, $fullyQualifiedName);
        }

        // C. is aliased classes matching the class name
        if ($useName === $className) {
            return new AliasedObjectType($alias, $fullyQualifiedName);
        }

        return null;
    }

    private function matchShortenedObjectType(
        Node $node,
        ObjectType $objectType
    ): ShortenedObjectType|ShortenedGenericObjectType|null {
        $uses = $this->useImportsResolver->resolveForNode($node);
        if ($uses === []) {
            return null;
        }

        foreach ($uses as $use) {
            foreach ($use->uses as $useUse) {
                if ($useUse->alias !== null) {
                    continue;
                }

                $partialNamespaceObjectType = $this->matchPartialNamespaceObjectType($objectType, $useUse);
                if ($partialNamespaceObjectType !== null) {
                    return $partialNamespaceObjectType;
                }

                $partialNamespaceObjectType = $this->matchClassWithLastUseImportPart($objectType, $useUse);
                if ($partialNamespaceObjectType instanceof FullyQualifiedObjectType) {
                    // keep Generic items
                    if ($objectType instanceof GenericObjectType) {
                        return new ShortenedGenericObjectType(
                            $objectType->getClassName(),
                            $objectType->getTypes(),
                            $partialNamespaceObjectType->getClassName()
                        );
                    }

                    return $partialNamespaceObjectType->getShortNameType();
                }

                if ($partialNamespaceObjectType instanceof ShortenedObjectType) {
                    return $partialNamespaceObjectType;
                }
            }
        }

        return null;
    }

    private function matchSameNamespacedObjectType(Node $node, ObjectType $objectType): ?FullyQualifiedObjectType
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return null;
        }

        $namespaceName = $scope->getNamespace();
        if ($namespaceName === null) {
            return null;
        }

        $namespacedObject = $namespaceName . '\\' . ltrim($objectType->getClassName(), '\\');

        if ($this->reflectionProvider->hasClass($namespacedObject)) {
            return new FullyQualifiedObjectType($namespacedObject);
        }

        return null;
    }

    private function matchPartialNamespaceObjectType(ObjectType $objectType, UseUse $useUse): ?ShortenedObjectType
    {
        // partial namespace
        if (! \str_starts_with($objectType->getClassName(), $useUse->name->getLast() . '\\')) {
            return null;
        }

        $classNameWithoutLastUsePart = Strings::after($objectType->getClassName(), '\\', 1);

        $connectedClassName = $useUse->name->toString() . '\\' . $classNameWithoutLastUsePart;
        if (! $this->reflectionProvider->hasClass($connectedClassName)) {
            return null;
        }

        if ($objectType->getClassName() === $connectedClassName) {
            return null;
        }

        return new ShortenedObjectType($objectType->getClassName(), $connectedClassName);
    }

    /**
     * @return FullyQualifiedObjectType|ShortenedObjectType|null
     */
    private function matchClassWithLastUseImportPart(ObjectType $objectType, UseUse $useUse): ?ObjectType
    {
        if ($useUse->name->getLast() !== $objectType->getClassName()) {
            return null;
        }

        if (! $this->reflectionProvider->hasClass($useUse->name->toString())) {
            return null;
        }

        if ($objectType->getClassName() === $useUse->name->toString()) {
            return new FullyQualifiedObjectType($objectType->getClassName());
        }

        return new ShortenedObjectType($objectType->getClassName(), $useUse->name->toString());
    }
}
