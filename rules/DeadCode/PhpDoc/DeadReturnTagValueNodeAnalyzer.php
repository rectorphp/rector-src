<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\DeadCode\PhpDoc\Guard\StandaloneTypeRemovalGuard;
use Rector\DeadCode\PhpDoc\Guard\TemplateTypeRemovalGuard;
use Rector\DeadCode\TypeNodeAnalyzer\GenericTypeNodeAnalyzer;
use Rector\DeadCode\TypeNodeAnalyzer\MixedArrayTypeNodeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class DeadReturnTagValueNodeAnalyzer
{
    public function __construct(
        private TypeComparator $typeComparator,
        private GenericTypeNodeAnalyzer $genericTypeNodeAnalyzer,
        private MixedArrayTypeNodeAnalyzer $mixedArrayTypeNodeAnalyzer,
        private StandaloneTypeRemovalGuard $standaloneTypeRemovalGuard,
        private PhpDocTypeChanger $phpDocTypeChanger,
        private StaticTypeMapper $staticTypeMapper,
        private TemplateTypeRemovalGuard $templateTypeRemovalGuard,
    ) {
    }

    public function isDead(ReturnTagValueNode $returnTagValueNode, ClassMethod|Function_ $functionLike): bool
    {
        $returnType = $functionLike->getReturnType();

        if ($returnType === null) {
            return false;
        }

        if ($returnTagValueNode->description !== '') {
            return false;
        }

        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $returnTagValueNode->type,
            $functionLike
        );
        if (! $this->templateTypeRemovalGuard->isLegal($docType)) {
            return false;
        }

        $scope = $functionLike->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope && $scope->isInTrait() && $returnTagValueNode->type instanceof ThisTypeNode) {
            return false;
        }

        if (! $this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $returnType,
            $returnTagValueNode->type,
            $functionLike,
        )) {
            return $this->isDeadNotEqual($returnTagValueNode, $returnType, $functionLike);
        }

        if ($this->phpDocTypeChanger->isAllowed($returnTagValueNode->type)) {
            return false;
        }

        if (! $returnTagValueNode->type instanceof BracketsAwareUnionTypeNode) {
            return $this->standaloneTypeRemovalGuard->isLegal($returnTagValueNode->type, $returnType);
        }

        if ($this->genericTypeNodeAnalyzer->hasGenericType($returnTagValueNode->type)) {
            return false;
        }

        if ($this->mixedArrayTypeNodeAnalyzer->hasMixedArrayType($returnTagValueNode->type)) {
            return false;
        }

        return ! $this->hasTrueFalsePseudoType($returnTagValueNode->type);
    }

    private function isVoidReturnType(Node $node): bool
    {
        return $node instanceof Identifier && $node->toString() === 'void';
    }

    private function isNeverReturnType(Node $node): bool
    {
        return $node instanceof Identifier && $node->toString() === 'never';
    }

    private function isDeadNotEqual(
        ReturnTagValueNode $returnTagValueNode,
        Node $node,
        ClassMethod|Function_ $functionLike
    ): bool {
        if ($returnTagValueNode->type instanceof IdentifierTypeNode && (string) $returnTagValueNode->type === 'void') {
            return true;
        }

        if (! $this->hasUsefullPhpdocType($returnTagValueNode, $node)) {
            return true;
        }

        $nodeType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($node);
        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $returnTagValueNode->type,
            $functionLike
        );

        return $docType instanceof UnionType && $this->typeComparator->areTypesEqual(
            TypeCombinator::removeNull($docType),
            $nodeType
        );
    }

    private function hasTrueFalsePseudoType(BracketsAwareUnionTypeNode $bracketsAwareUnionTypeNode): bool
    {
        $unionTypes = $bracketsAwareUnionTypeNode->types;

        foreach ($unionTypes as $unionType) {
            if (! $unionType instanceof IdentifierTypeNode) {
                continue;
            }

            $name = strtolower((string) $unionType);
            if (in_array($name, ['true', 'false'], true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * exact different between @return and node return type
     */
    private function hasUsefullPhpdocType(ReturnTagValueNode $returnTagValueNode, mixed $returnType): bool
    {
        if ($returnTagValueNode->type instanceof IdentifierTypeNode && $returnTagValueNode->type->name === 'mixed') {
            return false;
        }

        if (! $this->isVoidReturnType($returnType)) {
            return ! $this->isNeverReturnType($returnType);
        }

        if (! $returnTagValueNode->type instanceof IdentifierTypeNode || (string) $returnTagValueNode->type !== 'never') {
            return false;
        }

        return ! $this->isNeverReturnType($returnType);
    }
}
