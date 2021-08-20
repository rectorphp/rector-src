<?php

declare(strict_types=1);


namespace Rector\CodeQuality\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\NullType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\Core\NodeAnalyzer\ParamAnalyzer;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class ClassMethodReturnTypeManipulator
{
    public function __construct(
        private PhpDocInfoFactory $phpDocInfoFactory,
        private PhpDocTypeChanger $phpDocTypeChanger,
        private NodeTypeResolver $nodeTypeResolver,
        private ParamAnalyzer $paramAnalyzer
    ) {
    }

    public function changeReturnType(ClassMethod $classMethod): ?ClassMethod
    {
        /** @var FullyQualified|null $returnType */
        $returnType = $classMethod->returnType;
        if ($returnType === null) {
            return null;
        }

        $isNullable = false;
        if ($returnType instanceof NullableType) {
            $isNullable = true;
            $returnType = $returnType->type;
        }
        if (! $this->nodeTypeResolver->isObjectType($returnType, new ObjectType('DateTime'))) {
            return null;
        }

        $classMethod->returnType = new FullyQualified('DateTimeInterface');
        if ($isNullable) {
            $classMethod->returnType = new NullableType($classMethod->returnType);
        }


        $types = $this->determinePhpDocTypes($classMethod->returnType);
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $this->phpDocTypeChanger->changeReturnType($phpDocInfo, new UnionType($types));

        return $classMethod;
    }

    /**
     * @return Type[]
     */
    private function determinePhpDocTypes(Node $node): array
    {
        $types = [
            new ObjectType('DateTime'),
            new ObjectType('DateTimeImmutable')
        ];

        if ($this->canHaveNullType($node)) {
            $types[] = new NullType();
        }

        return $types;
    }

    private function canHaveNullType(Node $node): bool
    {
        if ($node instanceof Param) {
            return $this->paramAnalyzer->isNullable($node);
        }

        return $node instanceof NullableType;
    }
}
