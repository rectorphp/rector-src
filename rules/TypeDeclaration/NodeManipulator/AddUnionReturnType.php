<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\Constant\ConstantBooleanType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\ValueObject\Type\BracketsAwareUnionTypeNode;
use Rector\Comments\NodeDocBlock\DocBlockUpdater;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\TypeMapper\UnionTypeMapper;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

final readonly class AddUnionReturnType
{
    public function __construct(
        private ReturnTypeInferer $returnTypeInferer,
        private UnionTypeMapper $unionTypeMapper,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private DocBlockUpdater $docBlockUpdater,
    ) {
    }

    public function add(ClassMethod|Function_|Closure $node): ClassMethod|Function_|Closure|null
    {
        $inferReturnType = $this->returnTypeInferer->inferFunctionLike($node);
        if (! $inferReturnType instanceof UnionType) {
            return null;
        }

        $returnType = $this->unionTypeMapper->mapToPhpParserNode($inferReturnType, TypeKind::RETURN);
        if (! $returnType instanceof Node) {
            return null;
        }

        $this->mapStandaloneSubType($node, $inferReturnType);

        $node->returnType = $returnType;
        return $node;
    }

    private function mapStandaloneSubType(ClassMethod|Function_|Closure $node, UnionType $unionType): void
    {
        $value = null;

        foreach ($unionType->getTypes() as $type) {
            if ($type instanceof ConstantBooleanType) {
                $value = $type->getValue() ? 'true' : 'false';
                break;
            }
        }

        if ($value === null) {
            return;
        }

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $returnType = $phpDocInfo->getReturnTagValue();

        if (! $returnType instanceof ReturnTagValueNode) {
            return;
        }

        if (! $returnType->type instanceof BracketsAwareUnionTypeNode) {
            return;
        }

        foreach ($returnType->type->types as $key => $type) {
            if ($type instanceof IdentifierTypeNode && $type->__toString() === 'bool') {
                $returnType->type->types[$key] = new IdentifierTypeNode($value);
                $this->docBlockUpdater->updateRefactoredNodeWithPhpDocInfo($node);

                break;
            }
        }
    }
}
