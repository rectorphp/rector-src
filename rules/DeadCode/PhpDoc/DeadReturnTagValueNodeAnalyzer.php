<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node\Identifier;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\DeadCode\PhpDoc\Guard\StandaloneTypeRemovalGuard;
use Rector\DeadCode\TypeNodeAnalyzer\GenericTypeNodeAnalyzer;
use Rector\DeadCode\TypeNodeAnalyzer\MixedArrayTypeNodeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\StaticTypeMapper\StaticTypeMapper;

final class DeadReturnTagValueNodeAnalyzer
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly GenericTypeNodeAnalyzer $genericTypeNodeAnalyzer,
        private readonly MixedArrayTypeNodeAnalyzer $mixedArrayTypeNodeAnalyzer,
        private readonly StandaloneTypeRemovalGuard $standaloneTypeRemovalGuard,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
        private readonly StaticTypeMapper $staticTypeMapper
    ) {
    }

    public function isDead(ReturnTagValueNode $returnTagValueNode, ClassMethod $classMethod): bool
    {
        if ($returnTagValueNode->description !== '') {
            return false;
        }
        
        $returnType = $classMethod->getReturnType();
        if ($returnType === null) {
            return false;
        }

        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope && $scope->isInTrait() && $returnTagValueNode->type instanceof ThisTypeNode) {
            return false;
        }

        // in case of void, there is no added value in @return tag
        if ($this->isVoidReturnType($returnType)) {
            return ! $returnTagValueNode->type instanceof IdentifierTypeNode || (string) $returnTagValueNode->type !== 'never';
        }

        if (! $this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $returnType,
            $returnTagValueNode->type,
            $classMethod,
        )) {
            return $this->isDeadNotEqual($returnTagValueNode, $returnType, $classMethod);
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

    private function isDeadNotEqual(ReturnTagValueNode $returnTagValueNode, Node $node, ClassMethod $classMethod): bool
    {
        if ($returnTagValueNode->type instanceof IdentifierTypeNode && (string) $returnTagValueNode->type === 'void') {
            return true;
        }

        $nodeType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($node);
        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType(
            $returnTagValueNode->type,
            $classMethod
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
}
