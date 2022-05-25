<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Trait_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ThisTypeNode;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\DeadCode\TypeNodeAnalyzer\GenericTypeNodeAnalyzer;
use Rector\DeadCode\TypeNodeAnalyzer\MixedArrayTypeNodeAnalyzer;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;

final class DeadReturnTagValueNodeAnalyzer
{
    public function __construct(
        private readonly TypeComparator $typeComparator,
        private readonly BetterNodeFinder $betterNodeFinder,
        private readonly GenericTypeNodeAnalyzer $genericTypeNodeAnalyzer,
        private readonly MixedArrayTypeNodeAnalyzer $mixedArrayTypeNodeAnalyzer,
    ) {
    }

    public function isDead(ReturnTagValueNode $returnTagValueNode, FunctionLike $functionLike): bool
    {
        $returnType = $functionLike->getReturnType();
        if ($returnType === null) {
            return false;
        }

        $classLike = $this->betterNodeFinder->findParentType($functionLike, ClassLike::class);
        if ($classLike instanceof Trait_ && $returnTagValueNode->type instanceof ThisTypeNode) {
            return false;
        }

        if (! $this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $returnType,
            $returnTagValueNode->type,
            $functionLike,
        )) {
            return false;
        }

        if (in_array($returnTagValueNode->type::class, PhpDocTypeChanger::ALLOWED_TYPES, true)) {
            return false;
        }

        if (! $returnTagValueNode->type instanceof BracketsAwareUnionTypeNode) {
            return $returnTagValueNode->description === '';
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
