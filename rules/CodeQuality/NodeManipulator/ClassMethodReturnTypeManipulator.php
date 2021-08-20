<?php

declare(strict_types=1);


namespace Rector\CodeQuality\NodeManipulator;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ClassMethodReturnTypeManipulator
{
    public function __construct(
        private PhpDocInfoFactory $phpDocInfoFactory,
        private PhpDocTypeChanger $phpDocTypeChanger,
        private NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function changeReturnType(
        ClassMethod $classMethod,
        ObjectType $toReplaceType,
        Identifier|Name|NullableType|UnionType $replaceIntoType,
        Type $phpDocType
    ): ClassMethod {
        $returnType = $classMethod->returnType;
        if ($returnType === null) {
            return $classMethod;
        }

        $isNullable = false;
        if ($returnType instanceof NullableType) {
            $isNullable = true;
            $returnType = $returnType->type;
        }
        if (! $this->nodeTypeResolver->isObjectType($returnType, $toReplaceType)) {
            return $classMethod;
        }

        if ($isNullable && !$replaceIntoType instanceof NullableType) {
            $replaceIntoType = new NullableType($replaceIntoType);
        }
        $classMethod->returnType = $replaceIntoType;

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, $phpDocType);

        return $classMethod;
    }
}
