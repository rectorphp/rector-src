<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Class_;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Rector\Php74\Guard\MakePropertyTypedGuard;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\StaticTypeMapper\Mapper\ScalarStringToTypeMapper;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\AllAssignNodePropertyTypeInferer;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Class_\TypedPropertyFromJMSSerializerAttributeTypeRector\TypedPropertyFromJMSSerializerAttributeTypeRectorTest
 */
final class TypedPropertyFromJMSSerializerAttributeTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const JMS_TYPE = 'JMS\Serializer\Annotation\Type';

    public function __construct(
        private readonly AllAssignNodePropertyTypeInferer $allAssignNodePropertyTypeInferer,
        private readonly MakePropertyTypedGuard $makePropertyTypedGuard,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly ValueResolver $valueResolver,
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly ScalarStringToTypeMapper $scalarStringToTypeMapper,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add typed property from JMS Serializer Type attribute', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private $name;
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    #[\JMS\Serializer\Annotation\Type('string')]
    private ?string $name = null;
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
        return [Class_::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::ATTRIBUTES;
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;
        $classReflection = null;

        foreach ($node->getProperties() as $property) {
            if (! $property->isPrivate()) {
                continue;
            }

            if ($property->type instanceof Node) {
                continue;
            }

            if (! $this->phpAttributeAnalyzer->hasPhpAttribute($property, self::JMS_TYPE)) {
                continue;
            }

            if (! $classReflection instanceof ClassReflection) {
                $classReflection = $this->reflectionResolver->resolveClassReflection($node);
            }

            if (! $classReflection instanceof ClassReflection) {
                return null;
            }

            if (! $this->makePropertyTypedGuard->isLegal($property, $classReflection, false)) {
                continue;
            }

            $inferredType = $this->allAssignNodePropertyTypeInferer->inferProperty(
                $property,
                $classReflection,
                $this->file
            );
            // has assigned
            if ($inferredType instanceof Type) {
                continue;
            }

            if ($property->props[0]->default instanceof Node) {
                continue;
            }

            $typeValue = null;
            foreach ($property->attrGroups as $attrGroup) {
                foreach ($attrGroup->attrs as $attr) {
                    if ($attr->name->toString() === self::JMS_TYPE) {
                        $typeValue = $this->valueResolver->getValue($attr->args[0]->value);
                        break;
                    }
                }
            }

            if (! is_string($typeValue)) {
                continue;
            }

            $typeValue = Strings::match($typeValue, '#\w+#');
            if (isset($typeValue[0]) && is_string($typeValue[0])) {
                $type = $this->scalarStringToTypeMapper->mapScalarStringToType($typeValue[0]);
                if ($type instanceof MixedType) {
                    $type = new ObjectType($typeValue[0]);
                }

                $propertyType = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($type, TypeKind::PROPERTY);

                if (! $propertyType instanceof Identifier && ! $propertyType instanceof FullyQualified) {
                    return null;
                }

                $type = new NullableType($propertyType);

                $property->type = $type;
                $property->props[0]->default = new ConstFetch(new Name('null'));

                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }
}
