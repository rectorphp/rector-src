<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Contract\Rector\AllowEmptyConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\PhpDoc\TagRemover\VarTagRemover;
use Rector\Php74\Guard\MakePropertyTypedGuard;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\TypeDeclaration\NodeTypeAnalyzer\PropertyTypeDecorator;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\AllAssignNodePropertyTypeInferer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\TypedPropertyFromAssignsRectorTest
 */
final class TypedPropertyFromAssignsRector extends AbstractRector implements AllowEmptyConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const INLINE_PUBLIC = 'inline_public';

    /**
     * Default to false, which only apply changes:
     *
     *  – private modifier property
     *  - protected modifier property on final class without extends
     *
     * Set to true will allow change other modifiers as well as far as not forbidden, eg: callable type, null type, etc.
     */
    private bool $inlinePublic = false;

    public function __construct(
        private readonly AllAssignNodePropertyTypeInferer $allAssignNodePropertyTypeInferer,
        private readonly PropertyTypeDecorator $propertyTypeDecorator,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly VarTagRemover $varTagRemover,
        private readonly MakePropertyTypedGuard $makePropertyTypedGuard
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
CODE_SAMPLE,
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
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->makePropertyTypedGuard->isLegal($node, $this->inlinePublic)) {
            return null;
        }

        $inferredType = $this->allAssignNodePropertyTypeInferer->inferProperty($node);
        if (! $inferredType instanceof Type) {
            return null;
        }

        if ($inferredType instanceof MixedType) {
            return null;
        }

        $inferredType = $this->decorateTypeWithNullableIfDefaultPropertyNull($node, $inferredType);

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($inferredType, TypeKind::PROPERTY());
        if ($typeNode === null) {
            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $inferredType);
            return $node;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $inferredType);
            return $node;
        }

        // public property can be anything with not inline public configured
        if (! $node->isPrivate() && ! $this->inlinePublic) {
            $this->phpDocTypeChanger->changeVarType($phpDocInfo, $inferredType);
            return $node;
        }

        if ($inferredType instanceof UnionType) {
            $this->propertyTypeDecorator->decoratePropertyUnionType($inferredType, $typeNode, $node, $phpDocInfo);
        } else {
            $node->type = $typeNode;
        }

        $this->varTagRemover->removeVarTagIfUseless($phpDocInfo, $node);

        return $node;
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
