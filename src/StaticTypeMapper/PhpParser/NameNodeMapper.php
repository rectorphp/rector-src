<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\StaticType;
use PHPStan\Type\Type;
use Rector\Enum\ObjectReference;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\ParentObjectWithoutClassType;
use Rector\StaticTypeMapper\ValueObject\Type\ParentStaticType;
use Rector\StaticTypeMapper\ValueObject\Type\SelfStaticType;

/**
 * @implements PhpParserNodeMapperInterface<Name>
 */
final readonly class NameNodeMapper implements PhpParserNodeMapperInterface
{
    public function __construct(
        private ReflectionResolver $reflectionResolver,
        private FullyQualifiedNodeMapper $fullyQualifiedNodeMapper,
    ) {
    }

    public function getNodeType(): string
    {
        return Name::class;
    }

    /**
     * @param Name $node
     */
    public function mapToPHPStan(Node $node): Type
    {
        // ensure loop of PhpParserNodeMapperInterface[] based on file system not overlapped
        // with FullyQualifiedNodeMapper that need cover FullyQualified early
        // when Name instance of FullyQualified
        if ($node instanceof FullyQualified) {
            return $this->fullyQualifiedNodeMapper->mapToPHPStan($node);
        }

        $name = $node->toString();

        if ($node->isSpecialClassName()) {
            return $this->createClassReferenceType($node, $name);
        }

        return new MixedType();
    }

    private function createClassReferenceType(
        Name $name,
        string $reference
    ): MixedType | StaticType | SelfStaticType | ObjectWithoutClassType {
        $classReflection = $this->reflectionResolver->resolveClassReflection($name);
        if (! $classReflection instanceof ClassReflection) {
            return new MixedType();
        }

        if ($reference === ObjectReference::STATIC) {
            return new StaticType($classReflection);
        }

        if ($reference === ObjectReference::SELF) {
            return new SelfStaticType($classReflection);
        }

        $parentClassReflection = $classReflection->getParentClass();
        if ($parentClassReflection instanceof ClassReflection) {
            return new ParentStaticType($parentClassReflection);
        }

        return new ParentObjectWithoutClassType();
    }
}
