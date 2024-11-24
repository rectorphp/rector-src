<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\BooleanType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IterableType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StaticType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Enum\ObjectReference;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\Mapper\ScalarStringToTypeMapper;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ParentStaticType;
use Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType;
use Rector\TypeDeclaration\PHPStan\ObjectTypeSpecifier;

/**
 * @implements PhpDocTypeMapperInterface<IdentifierTypeNode>
 */
final readonly class IdentifierPhpDocTypeMapper implements PhpDocTypeMapperInterface
{
    public function __construct(
        private ObjectTypeSpecifier $objectTypeSpecifier,
        private ScalarStringToTypeMapper $scalarStringToTypeMapper,
        private ReflectionProvider $reflectionProvider,
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function getNodeType(): string
    {
        return IdentifierTypeNode::class;
    }

    /**
     * @param IdentifierTypeNode $typeNode
     */
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        return $this->mapIdentifierTypeNode($typeNode, $node);
    }

    public function mapIdentifierTypeNode(IdentifierTypeNode $identifierTypeNode, Node $node): Type
    {
        $type = $this->scalarStringToTypeMapper->mapScalarStringToType($identifierTypeNode->name);
        if (! $type instanceof MixedType) {
            return $type;
        }

        if ($type->isExplicitMixed()) {
            return $type;
        }

        $loweredName = strtolower($identifierTypeNode->name);
        if ($loweredName === ObjectReference::SELF) {
            return $this->mapSelf($node);
        }

        if ($loweredName === ObjectReference::PARENT) {
            return $this->mapParent($node);
        }

        if ($loweredName === ObjectReference::STATIC) {
            return $this->mapStatic($node);
        }

        if ($loweredName === 'iterable') {
            return new IterableType(new MixedType(), new MixedType());
        }

        $withPreslash = false;
        if (str_starts_with($identifierTypeNode->name, '\\')) {
            $typeWithoutPreslash = Strings::substring($identifierTypeNode->name, 1);
            $objectType = new FullyQualifiedObjectType($typeWithoutPreslash);
            $withPreslash = true;
        } else {
            if ($identifierTypeNode->name === 'scalar') {
                // pseudo type, see https://www.php.net/manual/en/language.types.intro.php
                $scalarTypes = [new BooleanType(), new StringType(), new IntegerType(), new FloatType()];
                return new UnionType($scalarTypes);
            }

            $identifierTypeNode->name = ltrim($identifierTypeNode->name, '@');
            $objectType = new ObjectType($identifierTypeNode->name);
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        return $this->objectTypeSpecifier->narrowToFullyQualifiedOrAliasedObjectType(
            $node,
            $objectType,
            $scope,
            $withPreslash
        );
    }

    private function mapSelf(Node $node): MixedType | SelfObjectType
    {
        // @todo check FQN
        $className = $this->resolveClassName($node);
        if (! is_string($className)) {
            // self outside the class, e.g. in a function
            return new MixedType();
        }

        return new SelfObjectType($className);
    }

    private function mapParent(Node $node): ParentStaticType | MixedType
    {
        $className = $this->resolveClassName($node);
        if (! is_string($className)) {
            // parent outside the class, e.g. in a function
            return new MixedType();
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return new MixedType();
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        $parentClassReflection = $classReflection->getParentClass();

        if (! $parentClassReflection instanceof ClassReflection) {
            return new MixedType();
        }

        return new ParentStaticType($parentClassReflection);
    }

    private function mapStatic(Node $node): MixedType | StaticType
    {
        $className = $this->resolveClassName($node);
        if (! is_string($className)) {
            // static outside the class, e.g. in a function
            return new MixedType();
        }

        if (! $this->reflectionProvider->hasClass($className)) {
            return new MixedType();
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        return new StaticType($classReflection);
    }

    private function resolveClassName(Node $node): ?string
    {
        $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        if (! $classReflection instanceof ClassReflection) {
            return null;
        }

        return $classReflection->getName();
    }
}
