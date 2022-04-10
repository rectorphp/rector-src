<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Guard\PhpDocNestedAnnotationGuard;
use Rector\TypeDeclaration\Helper\PhpDocNullableTypeHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector\ReturnAnnotationIncorrectNullableRectorTest
 */
final class ReturnAnnotationIncorrectNullableRector extends AbstractRector
{
    private PhpDocTypeChanger $phpDocTypeChanger;

    private PhpDocNullableTypeHelper $phpDocNullableTypeHelper;

    private PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard;

    public function __construct(
        PhpDocTypeChanger $phpDocTypeChanger,
        PhpDocNullableTypeHelper $phpDocNullableTypeHelper,
        PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard,
    ) {
        $this->phpDocTypeChanger = $phpDocTypeChanger;
        $this->phpDocNullableTypeHelper = $phpDocNullableTypeHelper;
        $this->phpDocNestedAnnotationGuard = $phpDocNestedAnnotationGuard;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add or remove null type from @return phpdoc typehint based on php return type declaration',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @return \DateTime[]
     */
    public function getDateTimes(): ?array
    {
        return $this->dateTimes;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @return \DateTime[]|null
     */
    public function getDateTimes(): ?array
    {
        return $this->dateTimes;
    }
}
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node): ?\PhpParser\Node
    {
        $returnType = $node->getReturnType();

        if ($returnType === null) {
            return null;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            return null;
        }

        if (! $this->phpDocNestedAnnotationGuard->isPhpDocCommentCorrectlyParsed($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $returnTagValueNode = $phpDocInfo->getReturnTagValue();
        if ($returnTagValueNode === null) {
            return null;
        }

        $phpStanDocTypeNode = $returnTagValueNode->type;
        $phpParserType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($returnType);
        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($phpStanDocTypeNode, $node);

        $updatedPhpDocType = $this->phpDocNullableTypeHelper->resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserType(
            $docType,
            $phpParserType
        );

        if ($updatedPhpDocType === null) {
            return null;
        }

        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, $updatedPhpDocType);

        return $node;
    }
}
