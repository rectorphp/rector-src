<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\DeadCode\PhpDoc\Guard\StandaloneTypeRemovalGuard;
use Rector\DeadCode\TypeNodeAnalyzer\GenericTypeNodeAnalyzer;
use Rector\DeadCode\TypeNodeAnalyzer\MixedArrayTypeNodeAnalyzer;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;

final class DeadReturnTagValueNodeAnalyzer
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly GenericTypeNodeAnalyzer $genericTypeNodeAnalyzer,
        private readonly MixedArrayTypeNodeAnalyzer $mixedArrayTypeNodeAnalyzer,
        private readonly StandaloneTypeRemovalGuard $standaloneTypeRemovalGuard,
        private readonly PhpDocTypeChanger $phpDocTypeChanger,
    ) {
    }

    public function isDead(ReturnTagValueNode $returnTagValueNode, ClassMethod $classMethod): bool
    {
        $returnType = $classMethod->getReturnType();
        if ($returnType === null) {
            return false;
        }

        $scope = $classMethod->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope && $scope->isInTrait() && $returnTagValueNode->type instanceof ThisTypeNode) {
            return false;
        }

        if (! $this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $returnType,
            $returnTagValueNode->type,
            $classMethod,
        )) {
            return false;
        }

        if ($this->phpDocTypeChanger->isAllowed($returnTagValueNode->type)) {
            return false;
        }

        if (! $returnTagValueNode->type instanceof BracketsAwareUnionTypeNode) {
            return $this->isIdentiferRemovalAllowed($returnTagValueNode, $returnType);
        }

        if ($this->genericTypeNodeAnalyzer->hasGenericType($returnTagValueNode->type)) {
            return false;
        }

        if ($this->mixedArrayTypeNodeAnalyzer->hasMixedArrayType($returnTagValueNode->type)) {
            return false;
        }

        if ($this->hasTruePseudoType($returnTagValueNode->type)) {
            return false;
        }

        return $returnTagValueNode->description === '';
    }

    private function isIdentiferRemovalAllowed(ReturnTagValueNode $returnTagValueNode, Node $node): bool
    {
        if ($returnTagValueNode->description === '') {
            return $this->standaloneTypeRemovalGuard->isLegal($returnTagValueNode->type, $node);
        }

        return false;
    }

    private function hasTruePseudoType(BracketsAwareUnionTypeNode $bracketsAwareUnionTypeNode): bool
    {
        $unionTypes = $bracketsAwareUnionTypeNode->types;

        foreach ($unionTypes as $unionType) {
            if (! $unionType instanceof IdentifierTypeNode) {
                continue;
            }

            $name = strtolower((string) $unionType);
            if ($name === 'true') {
                return true;
            }
        }

        return false;
    }
}
