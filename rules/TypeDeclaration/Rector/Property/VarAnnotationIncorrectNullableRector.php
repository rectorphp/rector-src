<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Property;

use function count;
use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\MixedType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Guard\PhpDocNestedAnnotationGuard;
use Rector\TypeDeclaration\Helper\PhpDocNullableTypeHelper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector\VarAnnotationIncorrectNullableRectorTest
 */
final class VarAnnotationIncorrectNullableRector extends AbstractRector
{
    private PhpDocTypeChanger $phpDocTypeChanger;

    private PhpDocNullableTypeHelper $phpDocNullableTypeHelper;

    private PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard;

    public function __construct(
        PhpDocTypeChanger $phpDocTypeChanger,
        PhpDocNullableTypeHelper $phpDocNullableTypeHelper,
        PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard
    ) {
        $this->phpDocTypeChanger = $phpDocTypeChanger;
        $this->phpDocNullableTypeHelper = $phpDocNullableTypeHelper;
        $this->phpDocNestedAnnotationGuard = $phpDocNestedAnnotationGuard;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add or remove null type from @var phpdoc typehint based on php property type declaration',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var DateTime[]
     */
    private ?array $dateTimes;
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @var DateTime[]|null
     */
    private ?array $dateTimes;
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
        return [Property::class];
    }

    /**
     * @param Property $node
     */
    public function refactor(\PhpParser\Node $node): ?\PhpParser\Node
    {
        if (count($node->props) !== 1) {
            return null;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            return null;
        }

        if (! $this->phpDocNestedAnnotationGuard->isPhpDocCommentCorrectlyParsed($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        if (! $this->isVarDocAlreadySet($phpDocInfo)) {
            return null;
        }

        if ($node->type === null) {
            return null;
        }

        $phpParserType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($node->type);

        $varTagValueNode = $phpDocInfo->getVarTagValueNode();
        if ($varTagValueNode === null || $varTagValueNode->type === null) {
            return null;
        }

        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($varTagValueNode->type, $node);

        $updatedPhpDocType = $this->phpDocNullableTypeHelper->resolveUpdatedPhpDocTypeFromPhpDocTypeAndPhpParserType(
            $docType,
            $phpParserType
        );

        if ($updatedPhpDocType === null) {
            return null;
        }

        $this->phpDocTypeChanger->changeVarType($phpDocInfo, $updatedPhpDocType);

        return $node;
    }

    private function isVarDocAlreadySet(PhpDocInfo $phpDocInfo): bool
    {
        foreach (['@var', '@phpstan-var', '@psalm-var'] as $tagName) {
            $varType = $phpDocInfo->getVarType($tagName);
            if (! $varType instanceof MixedType) {
                return true;
            }
        }

        return false;
    }
}
