<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\TypeDeclaration\Guard\PhpDocNestedAnnotationGuard;
use Rector\TypeDeclaration\Helper\PhpDocNullableTypeHelper;
use Rector\TypeDeclaration\PhpDocParser\ParamPhpDocNodeFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamAnnotationIncorrectNullableRector\ParamAnnotationIncorrectNullableRectorTest
 */
final class ParamAnnotationIncorrectNullableRector extends AbstractRector
{
    private TypeComparator $typeComparator;

    private PhpDocNullableTypeHelper $phpDocNullableTypeHelper;

    private PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard;

    private ParamPhpDocNodeFactory $paramPhpDocNodeFactory;

    public function __construct(
        TypeComparator $typeComparator,
        PhpDocNullableTypeHelper $phpDocNullableTypeHelper,
        PhpDocNestedAnnotationGuard $phpDocNestedAnnotationGuard,
        ParamPhpDocNodeFactory $paramPhpDocNodeFactory,
    ) {
        $this->typeComparator = $typeComparator;
        $this->phpDocNullableTypeHelper = $phpDocNullableTypeHelper;
        $this->phpDocNestedAnnotationGuard = $phpDocNestedAnnotationGuard;
        $this->paramPhpDocNodeFactory = $paramPhpDocNodeFactory;
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
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(\PhpParser\Node $node): ?\PhpParser\Node
    {
        if ($node->getParams() === []) {
            return null;
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::TYPED_PROPERTIES)) {
            return null;
        }

        if (! $this->phpDocNestedAnnotationGuard->isPhpDocCommentCorrectlyParsed($node)) {
            return null;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $docNode = $phpDocInfo->getPhpDocNode();
        $wasAnyParamTagUpdated = $this->wasUpdateOfParamTagsRequired($docNode, $node, $phpDocInfo);

        if (! $wasAnyParamTagUpdated) {
            return null;
        }

        return $node;
    }

    private function matchParamByName(string $desiredParamName, ClassMethod $functionLike): ?Param
    {
        foreach ($functionLike->getParams() as $param) {
            $paramName = $this->nodeNameResolver->getName($param);
            if ('$' . $paramName !== $desiredParamName) {
                continue;
            }

            return $param;
        }

        return null;
    }

    private function changeParamType(PhpDocInfo $phpDocInfo, Type $newType, Param $param, string $paramName): void
    {
        // better skip, could crash hard
        if ($phpDocInfo->hasInvalidTag('@param')) {
            return;
        }

        $phpDocType = $this->staticTypeMapper->mapPHPStanTypeToPHPStanPhpDocTypeNode(
            $newType,
            \Rector\PHPStanStaticTypeMapper\Enum\TypeKind::PARAM()
        );
        $paramTagValueNode = $phpDocInfo->getParamTagValueByName($paramName);
        // override existing type
        if ($paramTagValueNode !== null) {
            // already set
            $currentType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
                $paramTagValueNode->type,
                $param
            );
            if ($this->typeComparator->areTypesEqual($currentType, $newType)) {
                return;
            }
            $paramTagValueNode->type = $phpDocType;
        } else {
            $paramTagValueNode = $this->paramPhpDocNodeFactory->create($phpDocType, $param);
            $phpDocInfo->addTagValueNode($paramTagValueNode);
        }
    }

    /**
     * This method performs update of param tags if required - the name is a bit forced because of its return type :/
     */
    private function wasUpdateOfParamTagsRequired(PhpDocNode $docNode, ClassMethod $node, PhpDocInfo $phpDocInfo): bool
    {
        $paramTagValueNodes = $docNode->getParamTagValues();
        $paramTagWasUpdated = false;
        foreach ($paramTagValueNodes as $paramTagValueNode) {
            if ($paramTagValueNode->type === null) {
                continue;
            }

            $param = $this->matchParamByName($paramTagValueNode->parameterName, $node);
            if (! $param instanceof Param) {
                continue;
            }

            $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($paramTagValueNode->type, $node);
            $updatedPhpDocType = $this->phpDocNullableTypeHelper->resolveUpdatedPhpDocTypeFromPhpDocTypeAndParamNode(
                $docType,
                $param
            );

            if ($updatedPhpDocType === null) {
                continue;
            }

            $this->changeParamType($phpDocInfo, $updatedPhpDocType, $param, $paramTagValueNode->parameterName);
            $paramTagWasUpdated = true;
        }

        return $paramTagWasUpdated;
    }
}
