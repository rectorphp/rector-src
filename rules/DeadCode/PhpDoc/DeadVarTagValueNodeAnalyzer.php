<?php

declare(strict_types=1);

namespace Rector\DeadCode\PhpDoc;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\DeadCode\PhpDoc\Guard\TemplateTypeRemovalGuard;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\PHPStan\ScopeFetcher;
use Rector\StaticTypeMapper\StaticTypeMapper;

final readonly class DeadVarTagValueNodeAnalyzer
{
    public function __construct(
        private TypeComparator $typeComparator,
        private StaticTypeMapper $staticTypeMapper,
        private TemplateTypeRemovalGuard $templateTypeRemovalGuard,
    ) {
    }

    public function isDead(VarTagValueNode $varTagValueNode, Property|ClassConst|Expression $node): bool
    {
        if (! $node instanceof Expression && ! $node->type instanceof Node) {
            return false;
        }

        if ($varTagValueNode->description !== '') {
            return false;
        }

        $targetNode = null;

        if ($node instanceof Expression && $node->expr instanceof Assign) {
            $targetNode = $node->expr->expr;
        } elseif ($node instanceof Property || $node instanceof ClassConst) {
            $targetNode = $node->type;
        }

        // allow Identifier, ComplexType, and Name on Property and ClassConst
        if (! $targetNode instanceof Node) {
            return false;
        }

        if ($varTagValueNode->type instanceof GenericTypeNode) {
            return false;
        }

        // is strict type superior to doc type? keep strict type only
        $propertyType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($targetNode);
        $docType = $this->staticTypeMapper->mapPHPStanPhpDocTypeNodeToPHPStanType($varTagValueNode->type, $node);


        if ($node instanceof Expression) {
            $scope = ScopeFetcher::fetch($node);

            // only allow Expr on assign expr
            if (! $targetNode instanceof Expr) {
                return false;
            }

            $nativeType = $scope->getNativeType($targetNode);
            if (! $docType->equals($nativeType)) {
                return false;
            }
        }

        if (! $this->templateTypeRemovalGuard->isLegal($docType)) {
            return false;
        }

        if ($propertyType instanceof UnionType && ! $docType instanceof UnionType) {
            return ! $docType instanceof IntersectionType;
        }

        if ($propertyType instanceof ObjectType && $docType instanceof ObjectType) {
            // more specific type is already in the property
            return $docType->isSuperTypeOf($propertyType)
                ->yes();
        }

        if ($this->typeComparator->arePhpParserAndPhpStanPhpDocTypesEqual(
            $targetNode,
            $varTagValueNode->type,
            $node
        )) {
            return true;
        }

        return $docType instanceof UnionType && $this->typeComparator->areTypesEqual(
            TypeCombinator::removeNull($docType),
            $propertyType
        );
    }
}
