<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\Generic\TemplateType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\DeadCode\PhpDoc\Guard\StandaloneTypeRemovalGuard;
use Rector\DeadCode\TypeNodeAnalyzer\GenericTypeNodeAnalyzer;
use Rector\DeadCode\TypeNodeAnalyzer\MixedArrayTypeNodeAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\NodeAnalyzer\ParamAnalyzer;

final readonly class DeadParamTagValueNodeAnalyzer
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private TypeComparator $typeComparator,
        private GenericTypeNodeAnalyzer $genericTypeNodeAnalyzer,
        private MixedArrayTypeNodeAnalyzer $mixedArrayTypeNodeAnalyzer,
        private ParamAnalyzer $paramAnalyzer,
        private PhpDocTypeChanger $phpDocTypeChanger,
        private StandaloneTypeRemovalGuard $standaloneTypeRemovalGuard,
        private StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function isDead(ParamTagValueNode $paramTagValueNode, FunctionLike $functionLike): bool
    {
        $param = $this->paramAnalyzer->getParamByName($paramTagValueNode->parameterName, $functionLike);
        if (! $param instanceof Param) {
            return false;
        }

        if ($param->type === null) {
            return false;
        }

        if ($paramTagValueNode->description !== '') {
            return false;
        }

        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $paramTagValueNode->type,
            $functionLike
        );
        if ($docType instanceof TemplateType) {
            return false;
        }

        if ($param->type instanceof Name && $this->nodeNameResolver->isName($param->type, 'object')) {
            return $paramTagValueNode->type instanceof IdentifierTypeNode && (string) $paramTagValueNode->type === 'object';
        }

        if (! $this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $param->type,
            $paramTagValueNode->type,
            $functionLike
        )) {
            return false;
        }

        if ($this->phpDocTypeChanger->isAllowed($paramTagValueNode->type)) {
            return false;
        }

        if (! $paramTagValueNode->type instanceof BracketsAwareUnionTypeNode) {
            return $this->standaloneTypeRemovalGuard->isLegal($paramTagValueNode->type, $param->type);
        }

        return $this->isAllowedBracketAwareUnion($paramTagValueNode->type);
    }

    private function isAllowedBracketAwareUnion(BracketsAwareUnionTypeNode $bracketsAwareUnionTypeNode): bool
    {
        if ($this->mixedArrayTypeNodeAnalyzer->hasMixedArrayType($bracketsAwareUnionTypeNode)) {
            return false;
        }

        return ! $this->genericTypeNodeAnalyzer->hasGenericType($bracketsAwareUnionTypeNode);
    }
}
