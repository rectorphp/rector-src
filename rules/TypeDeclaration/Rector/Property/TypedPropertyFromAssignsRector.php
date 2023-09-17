<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\Php74\Guard\MakePropertyTypedGuard;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\TypeDeclaration\NodeTypeAnalyzer\PropertyTypeDecorator;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\AllAssignNodePropertyTypeInferer;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\TypedPropertyFromAssignsRectorTest
 */
final class TypedPropertyFromAssignsRector extends AbstractRector implements MinPhpVersionInterface, ConfigurableRectorInterface
{
    /**
     * @api
     * @var string
     */
    public const INLINE_PUBLIC = 'inline_public';

    /**
     * Default to false, which only apply changes:
     *
     *  – private modifier property
     *  - protected modifier property on final class without extends or has extends but property and/or its usage only in current class
     *
     * Set to true will allow change other modifiers as well as far as not forbidden, eg: callable type, null type, etc.
     */
    private bool $inlinePublic = false;

    public function __construct(
        private readonly AllAssignNodePropertyTypeInferer $allAssignNodePropertyTypeInferer,
        private readonly PropertyTypeDecorator $propertyTypeDecorator,
        private readonly VarTagRemover $varTagRemover,
        private readonly MakePropertyTypedGuard $makePropertyTypedGuard,
        private readonly ReflectionResolver $reflectionResolver
    ) {
    }

    public function configure(array $configuration): void
    {
        $this->inlinePublic = $configuration[self::INLINE_PUBLIC] ?? (bool) current($configuration);
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add typed property from assigned types', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private $name;

    public function run()
    {
        $this->name = 'string';
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private string|null $name = null;

    public function run()
    {
        $this->name = 'string';
    }
}
CODE_SAMPLE
                ,
                [
                    self::INLINE_PUBLIC => false,
                ]
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
        return PhpVersionFeature::TYPED_PROPERTIES;
    }

    /**
     * @param Node\Stmt\Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;
        $classReflection = null;

        foreach ($node->getProperties() as $property) {
            // non-private property can be anything with not inline public configured
            if (! $property->isPrivate() && ! $this->inlinePublic) {
                continue;
            }

            if (! $classReflection instanceof ClassReflection) {
                $classReflection = $this->reflectionResolver->resolveClassReflection($node);
            }

            if (! $classReflection instanceof ClassReflection) {
                return null;
            }

            if (! $this->makePropertyTypedGuard->isLegal($property, $classReflection, $this->inlinePublic)) {
                continue;
            }

            $inferredType = $this->allAssignNodePropertyTypeInferer->inferProperty($property, $classReflection);
            if (! $inferredType instanceof Type) {
                continue;
            }

            if ($inferredType instanceof MixedType) {
                continue;
            }

            $inferredType = $this->decorateTypeWithNullableIfDefaultPropertyNull($property, $inferredType);
            $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($inferredType, TypeKind::PROPERTY);
            if (! $typeNode instanceof Node) {
                continue;
            }

            $hasChanged = true;

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
            if ($inferredType instanceof UnionType) {
                $this->propertyTypeDecorator->decoratePropertyUnionType(
                    $inferredType,
                    $typeNode,
                    $property,
                    $phpDocInfo,
                    false
                );
            } else {
                $property->type = $typeNode;
            }

            if (! $property->type instanceof Node) {
                continue;
            }

            $this->varTagRemover->removeVarTagIfUseless($phpDocInfo, $property);
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function decorateTypeWithNullableIfDefaultPropertyNull(Property $property, Type $inferredType): Type
    {
        $defaultExpr = $property->props[0]->default;
        if (! $defaultExpr instanceof Expr) {
            return $inferredType;
        }

        if (! $this->valueResolver->isNull($defaultExpr)) {
            return $inferredType;
        }

        if (TypeCombinator::containsNull($inferredType)) {
            return $inferredType;
        }

        return TypeCombinator::addNull($inferredType);
    }
}
