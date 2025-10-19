<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Type\Constant\ConstantArrayType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclarationDocblocks\NodeFinder\ReturnNodeFinder;
use Rector\TypeDeclarationDocblocks\TagNodeAnalyzer\UsefulArrayTagNodeAnalyzer;
use Rector\TypeDeclarationDocblocks\TypeResolver\ConstantArrayTypeGeneralizer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\DocblockReturnArrayFromDirectArrayInstanceRectorTest
 */
final class DocblockReturnArrayFromDirectArrayInstanceRector extends AbstractRector
{
    public function __construct(
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly ConstantArrayTypeGeneralizer $constantArrayTypeGeneralizer,
        private readonly ReturnNodeFinder $returnNodeFinder,
        private readonly UsefulArrayTagNodeAnalyzer $usefulArrayTagNodeAnalyzer
    ) {
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add simple @return array docblock based on direct single level direct return of []',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function getItems(): array
    {
        return [
            'hey' => 'now',
        ];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    /**
     * @return array<string, string>
     */
    public function getItems(): array
    {
        return [
            'hey' => 'now',
        ];
    }
}
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getStmts() === []) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        if ($this->usefulArrayTagNodeAnalyzer->isUsefulArrayTag($phpDocInfo->getReturnTagValue())) {
            return null;
        }

        $soleReturn = $this->returnNodeFinder->findOnlyReturnWithExpr($node);
        if (! $soleReturn instanceof Return_) {
            return null;
        }

        if (! $soleReturn->expr instanceof Array_) {
            return null;
        }

        // resolve simple type
        $returnedType = $this->getType($soleReturn->expr);
        if (! $returnedType instanceof ConstantArrayType) {
            return null;
        }

        if ($returnedType->getReferencedClasses() !== []) {
            // better handled by shared-interface/class rule, to avoid turning objects to mixed
            return null;
        }

        $genericTypeNode = $this->constantArrayTypeGeneralizer->generalize($returnedType);
        $this->phpDocTypeChanger->changeReturnTypeNode($node, $phpDocInfo, $genericTypeNode);

        return $node;
    }
}
