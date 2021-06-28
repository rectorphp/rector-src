<?php

declare(strict_types=1);

namespace Rector\Php74\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\UnionType as PhpParserUnionType;
use PhpParser\Parser;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\TemplateType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\NodeAnalyzer\PropertyFetchAnalyzer;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStanStaticTypeMapper\DoctrineTypeAnalyzer;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer;
use Rector\VendorLocker\VendorLockResolver;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\SmartFileSystem\SmartFileSystem;
use PHPStan\Reflection\ClassReflection;

/**
 * @changelog https://wiki.php.net/rfc/typed_properties_v2#proposal
 *
 * @see \Rector\Tests\Php74\Rector\Property\TypedPropertyRector\TypedPropertyRectorTest
 * @see \Rector\Tests\Php74\Rector\Property\TypedPropertyRector\ClassLikeTypesOnlyTest
 * @see \Rector\Tests\Php74\Rector\Property\TypedPropertyRector\DoctrineTypedPropertyRectorTest
 * @see \Rector\Tests\Php74\Rector\Property\TypedPropertyRector\ImportedTest
 * @see \Rector\Tests\Php74\Rector\Property\TypedPropertyRector\UnionTypedPropertyRectorTest
 */
final class TypedPropertyRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const CLASS_LIKE_TYPE_ONLY = 'class_like_type_only';

    /**
     * Useful for refactoring of huge applications. Taking types first narrows scope
     */
    private bool $classLikeTypeOnly = false;

    public function __construct(
        private PropertyTypeInferer $propertyTypeInferer,
        private VendorLockResolver $vendorLockResolver,
        private DoctrineTypeAnalyzer $doctrineTypeAnalyzer,
        private VarTagRemover $varTagRemover,
        private ReflectionProvider $reflectionProvider,
        private PropertyFetchAnalyzer $propertyFetchAnalyzer,
        private Parser $parser,
        private SmartFileSystem $smartFileSystem
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Changes property `@var` annotations from annotation to type.',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var int
     */
    private count;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    private int count;
}
CODE_SAMPLE
                    ,
                    [
                        self::CLASS_LIKE_TYPE_ONLY => false,
                    ]
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            return null;
        }

        if ($this->shouldSkipProperty($node)) {
            return null;
        }

        $varType = $this->propertyTypeInferer->inferProperty($node);
        if ($varType instanceof MixedType) {
            return null;
        }

        if ($varType instanceof UnionType) {
            $types = $varType->getTypes();
            if (count($types) === 2 && $types[0] instanceof TemplateType) {
                $node->type = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                    $types[0]->getBound(),
                    TypeKind::KIND_PROPERTY
                );

                return $node;
            }
        }

        $propertyTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
            $varType,
            TypeKind::KIND_PROPERTY
        );

        if ($this->isNullOrNonClassLikeTypeOrMixedOrVendorLockedIn($propertyTypeNode, $node)) {
            return null;
        }

        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if (! $varType instanceof UnionType && $scope instanceof Scope) {
            /** @var ClassReflection $classReflection */
            $classReflection = $scope->getClassReflection();
            $ancestors = $classReflection->getAncestors();
            $propertyName = $this->getName($node);

            $kindPropertyFetch = $node->isStatic()
                ? StaticPropertyFetch::class
                : PropertyFetch::class;

            foreach ($ancestors as $ancestor) {
                $fileName = (string) $ancestor->getFileName();
                $fileContent = $this->smartFileSystem->readFile($fileName);
                $nodes = $this->parser->parse($fileContent);

                if (is_a($ancestor->getName(), 'PHPUnit\Framework\TestCase', true)) {
                    continue;
                }

                $isFilled = (bool) $this->betterNodeFinder->findFirst((array) $nodes, function (Node $n) use ($propertyName, $kindPropertyFetch) {
                    if (! $n instanceof ClassMethod) {
                        return false;
                    }

                    if ($this->isNames($n->name, ['autowire', 'setUp', '__construct'])) {
                        return false;
                    }

                    return (bool) $this->betterNodeFinder->findFirst((array) $n->stmts, function (Node $n2) use (
                        $propertyName,
                        $kindPropertyFetch
                    ) {
                        if (! $n2 instanceof Assign) {
                            return false;
                        }

                        return is_a($n2->var, $kindPropertyFetch, true) && $this->isName($n2->var, $propertyName);
                    });
                });

                if ($isFilled) {
                    $varType = new UnionType([$varType, new NullType()]);
                    $propertyTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode(
                        $varType,
                        TypeKind::KIND_PROPERTY
                    );
                    break;
                }
            }
        }

        $this->varTagRemover->removeVarPhpTagValueNodeIfNotComment($node, $varType);
        $this->removeDefaultValueForDoctrineCollection($node, $varType);
        $this->addDefaultValueNullForNullableType($node, $varType);

        $node->type = $propertyTypeNode;

        return $node;
    }

    /**
     * @param array<string, bool> $configuration
     */
    public function configure(array $configuration): void
    {
        $this->classLikeTypeOnly = $configuration[self::CLASS_LIKE_TYPE_ONLY] ?? false;
    }

    /**
     * @param Name|NullableType|PhpParserUnionType|null $node
     */
    private function isNullOrNonClassLikeTypeOrMixedOrVendorLockedIn(?Node $node, Property $property): bool
    {
        if (! $node instanceof Node) {
            return true;
        }

        // is not class-type and should be skipped
        if ($this->shouldSkipNonClassLikeType($node)) {
            return true;
        }

        // false positive
        if (! $node instanceof Name) {
            return $this->vendorLockResolver->isPropertyTypeChangeVendorLockedIn($property);
        }

        if (! $this->isName($node, 'mixed')) {
            return $this->vendorLockResolver->isPropertyTypeChangeVendorLockedIn($property);
        }

        return true;
    }

    /**
     * @param Name|NullableType|PhpParserUnionType $node
     */
    private function shouldSkipNonClassLikeType(Node $node): bool
    {
        // unwrap nullable type
        if ($node instanceof NullableType) {
            $node = $node->type;
        }

        $typeName = $this->getName($node);
        if ($typeName === null) {
            return false;
        }

        if ($typeName === 'null') {
            return true;
        }

        if ($typeName === 'callable') {
            return true;
        }

        if (! $this->classLikeTypeOnly) {
            return false;
        }

        return ! $this->reflectionProvider->hasClass($typeName);
    }

    private function removeDefaultValueForDoctrineCollection(Property $property, Type $propertyType): void
    {
        if (! $this->doctrineTypeAnalyzer->isDoctrineCollectionWithIterableUnionType($propertyType)) {
            return;
        }

        $onlyProperty = $property->props[0];
        $onlyProperty->default = null;
    }

    private function addDefaultValueNullForNullableType(Property $property, Type $propertyType): void
    {
        if (! $propertyType instanceof UnionType) {
            return;
        }

        if (! $propertyType->isSuperTypeOf(new NullType())->yes()) {
            return;
        }

        $onlyProperty = $property->props[0];

        // skip is already has value
        if ($onlyProperty->default !== null) {
            return;
        }

        if ($this->propertyFetchAnalyzer->isFilledByConstructParam($property)) {
            return;
        }

        $onlyProperty->default = $this->nodeFactory->createNull();
    }

    private function shouldSkipProperty(Property $property): bool
    {
        // type is already set → skip
        if ($property->type !== null) {
            return true;
        }

        // skip multiple properties
        return count($property->props) > 1;
    }
}
