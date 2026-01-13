<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclarationDocblocks\NodeDocblockTypeDecorator;
use Rector\TypeDeclarationDocblocks\NodeFinder\ArrayDimFetchFinder;
use Rector\TypeDeclarationDocblocks\TagNodeAnalyzer\UsefulArrayTagNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\AddVarArrayDocblockFromDimFetchAssignRectorTest
 */
final class AddVarArrayDocblockFromDimFetchAssignRector extends AbstractRector
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly UsefulArrayTagNodeAnalyzer $usefulArrayTagNodeAnalyzer,
        private readonly NodeDocblockTypeDecorator $nodeDocblockTypeDecorator,
        private readonly ArrayDimFetchFinder $arrayDimFetchFinder,
    ) {

    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add @param array docblock if array_map is used on the parameter', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private array $items = [];

    public function run()
    {
        $this->items[] = [
            'name' => 'John',
        ];
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var array<array<string, string>>
     */
    private array $items = [];

    public function run()
    {
        $this->items[] = [
            'name' => 'John',
        ];
    }
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

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach ($node->getProperties() as $property) {
            if (! $this->isPropertyTypeArray($property)) {
                continue;
            }

            $propertyPhpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);

            if ($this->usefulArrayTagNodeAnalyzer->isUsefulArrayTag($propertyPhpDocInfo->getVarTagValueNode())) {
                continue;
            }

            $propertyName = $this->getName($property);

            $assignedExprs = $this->arrayDimFetchFinder->findDimFetchAssignToPropertyName($node, $propertyName);

            $assignedExprTypes = [];
            foreach ($assignedExprs as $assignedExpr) {
                $assignedExprTypes[] = $this->getType($assignedExpr);
            }

            // nothing to add
            if ($assignedExprTypes === []) {
                continue;
            }

            $uniqueGeneralizedUnionTypes = $this->typeFactory->uniquateTypes($assignedExprTypes);

            if (count($uniqueGeneralizedUnionTypes) > 1) {
                $generalizedUnionedTypes = new UnionType($uniqueGeneralizedUnionTypes);
            } else {
                $generalizedUnionedTypes = $uniqueGeneralizedUnionTypes[0];
            }

            $arrayReturnType = new ArrayType(new MixedType(), $generalizedUnionedTypes);

            $hasPropertyChanged = $this->nodeDocblockTypeDecorator->decorateGenericIterableVarType(
                $arrayReturnType,
                $propertyPhpDocInfo,
                $property
            );

            if ($hasPropertyChanged === false) {
                continue;
            }

            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    private function isPropertyTypeArray(Property $property): bool
    {
        if (! $property->type instanceof Identifier) {
            return false;
        }

        return $this->isName($property->type, 'array');
    }
}
