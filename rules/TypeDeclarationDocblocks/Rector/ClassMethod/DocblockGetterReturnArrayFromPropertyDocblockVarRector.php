<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclarationDocblocks\TagNodeAnalyzer\UsefulArrayTagNodeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockGetterReturnArrayFromPropertyDocblockVarRector\DocblockGetterReturnArrayFromPropertyDocblockVarRectorTest
 */
final class DocblockGetterReturnArrayFromPropertyDocblockVarRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly StaticTypeMapper $staticTypeMapper,
        private readonly UsefulArrayTagNodeAnalyzer $usefulArrayTagNodeAnalyzer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger
    ) {
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add @return array docblock to a getter method based on @var of the property', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private array $items;

    public function getItems(): array
    {
        return $this->items;
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @var int[]
     */
    private array $items;

    /**
     * @return int[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->returnType instanceof Node) {
            return null;
        }

        if (! $this->isName($node->returnType, 'array')) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        if ($this->usefulArrayTagNodeAnalyzer->isUsefulArrayTag($phpDocInfo->getReturnTagValue())) {
            return null;
        }

        //        // return tag is already given
        //        if ($phpDocInfo->getReturnTagValue() instanceof ReturnTagValueNode) {
        //            return null;
        //        }

        $propertyFetch = $this->matchReturnLocalPropertyFetch($node);
        if (! $propertyFetch instanceof PropertyFetch) {
            return null;
        }

        $propertyFetchType = $this->getType($propertyFetch);
        if ($propertyFetchType instanceof ArrayType
            && $propertyFetchType->getKeyType() instanceof MixedType
            && $propertyFetchType->getItemType() instanceof MixedType
        ) {
            return null;
        }

        if ($propertyFetchType instanceof UnionType) {
            return null;
        }

        $propertyFetchDocTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($propertyFetchType);

        $this->phpDocTypeChanger->changeReturnTypeNode($node, $phpDocInfo, $propertyFetchDocTypeNode);

        //        $returnTagValueNode = new ReturnTagValueNode($propertyFetchDocTypeNode, '');
        //        $phpDocInfo->addTagValueNode($returnTagValueNode);
        //
        //        $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

        return $node;
    }

    private function matchReturnLocalPropertyFetch(ClassMethod $classMethod): ?PropertyFetch
    {
        // we need exactly one statement of return
        if ($classMethod->stmts === null || count($classMethod->stmts) !== 1) {
            return null;
        }

        $onlyStmt = $classMethod->stmts[0];
        if (! $onlyStmt instanceof Return_) {
            return null;
        }

        if (! $onlyStmt->expr instanceof PropertyFetch) {
            return null;
        }

        $propertyFetch = $onlyStmt->expr;
        if (! $this->isName($propertyFetch->var, 'this')) {
            return null;
        }

        return $propertyFetch;
    }
}
