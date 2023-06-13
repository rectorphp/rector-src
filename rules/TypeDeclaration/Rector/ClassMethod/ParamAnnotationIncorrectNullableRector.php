<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\TypeDeclaration\Guard\PhpDocNestedAnnotationGuard;
use Rector\TypeDeclaration\Helper\PhpDocNullableTypeHelper;
use Rector\TypeDeclaration\NodeAnalyzer\ParamAnalyzer;
use Rector\TypeDeclaration\PhpDocParser\ParamPhpDocNodeFactory;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamAnnotationIncorrectNullableRector\ParamAnnotationIncorrectNullableRectorTest
 */
final class ParamAnnotationIncorrectNullableRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly PhpDocNullableTypeHelper $phpDocNullableTypeHelper,
        private readonly PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard,
        private readonly ParamPhpDocNodeFactory $paramPhpDocNodeFactory,
        private readonly ParamAnalyzer $paramAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add or remove null type from @param phpdoc typehint based on php parameter type declaration',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @param \DateTime[] $dateTimes
     */
    public function setDateTimes(?array $dateTimes): self
    {
        $this->dateTimes = $dateTimes;

        return $this;
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
final class SomeClass
{
    /**
     * @param \DateTime[]|null $dateTimes
     */
    public function setDateTimes(?array $dateTimes): self
    {
        $this->dateTimes = $dateTimes;

        return $this;
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
        return [ClassMethod::class, Function_::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_PROPERTIES;
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->getParams() === []) {
            return null;
        }

        if (! $this->phpDocNestedAnnotationGuard->isPhpDocCommentCorrectlyParsed($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $phpDocNode = $phpDocInfo->getPhpDocNode();

        return $this->updateParamTagsIfRequired($phpDocNode, $node, $phpDocInfo);
    }

    private function wasUpdateOfParamTypeRequired(
        PhpDocInfo $phpDocInfo,
        Type $newType,
        Param $param,
        string $paramName
    ): bool {
        // better skip, could crash hard
        if ($phpDocInfo->hasInvalidTag('@param')) {
            return false;
        }

        $typeNode = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode($newType);
        $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramName);
        // override existing type
        if ($paramTagValueNode instanceof ParamTagValueNode) {
            // already set
            $currentType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $paramTagValueNode->type,
                $param
            );
            if ($this->typeComparator->areTypesEqual($currentType, $newType)) {
                return false;
            }

            $paramTagValueNode->type = $typeNode;
        } else {
            $paramTagValueNode = $this->paramPhpDocNodeFactory->create($typeNode, $param);
            $phpDocInfo->addTagValueNode($paramTagValueNode);
        }

        return true;
    }

    /**
     * @return ClassMethod|Function_|null
     */
    private function updateParamTagsIfRequired(
        PhpDocNode $phpDocNode,
        ClassMethod|Function_ $node,
        PhpDocInfo $phpDocInfo
    ): ?Node {
        $paramTagValueNodes = $phpDocNode->getParamTagValues();
        $paramTagWasUpdated = false;
        foreach ($paramTagValueNodes as $paramTagValueNode) {
            $param = $this->paramAnalyzer->getParamByName($paramTagValueNode->parameterName, $node);
            if (! $param instanceof Param) {
                continue;
            }

            $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $paramTagValueNode->type,
                $node
            );
            $updatedPhpDocType = $this->phpDocNullableTypeHelper->resolveUpdatedPhpDocTypeFromPhpDocTypeAndParamNode(
                $docType,
                $param
            );

            if (! $updatedPhpDocType instanceof Type) {
                continue;
            }

            if ($this->wasUpdateOfParamTypeRequired(
                $phpDocInfo,
                $updatedPhpDocType,
                $param,
                $paramTagValueNode->parameterName
            )) {
                $paramTagWasUpdated = true;
            }
        }

        return $paramTagWasUpdated ? $node : null;
    }
}
