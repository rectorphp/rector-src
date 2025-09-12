<?php

declare(strict_types=1);

namespace Rector\TypeDeclarationDocblocks\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Rector\AbstractRector;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector\AddReturnDocblockForCommonObjectDenominatorRectorTest
 */
final class AddReturnDocblockForCommonObjectDenominatorRector extends AbstractRector
{
    public function __construct(
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly ReturnAnalyzer $returnAnalyzer,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add @return docblock array of objects, that have common denominator interface/parent class',
            [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class ExtensionProvider
{
    public function getExtensions(): array
    {
        return [
            new FirstExtension(),
            new SecondExtension(),
        ];
    }
}

class FirstExtension implements ExtensionInterface
{
}

class SecondExtension implements ExtensionInterface
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
final class ExtensionProvider
{
    /**
     * @return ExtensionInterface[]
     */
    public function getExtensions(): array
    {
        return [
            new FirstExtension(),
            new SecondExtension(),
        ];
    }
}

class FirstExtension implements ExtensionInterface
{
}

class SecondExtension implements ExtensionInterface
{
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $returnType = $phpDocInfo->getReturnType();

        if (! $returnType instanceof MixedType || $returnType->isExplicitMixed()) {
            return null;
        }

        dump(123);
        die;

        if ($node->returnType instanceof Node && ! $this->isName($node->returnType, 'array')) {
            return null;
        }

        $returnsScoped = $this->betterNodeFinder->findReturnsScoped($node);

        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($node, $returnsScoped)) {
            return null;
        }

        //        $arrayType = new ArrayType(new MixedType(), $firstScalarType);
        //
        //        $hasChanged = $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $arrayType);
        //        if ($hasChanged) {
        //            return $node;
        //        }

        return null;
    }
}
