<?php

declare(strict_types=1);

namespace Rector\Privatization\NodeFactory;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Privatization\Naming\ConstantNaming;

final class ClassConstantFactory
{
    public function __construct(
        private ConstantNaming $constantNaming,
        private PhpDocInfoFactory $phpDocInfoFactory
    ) {
    }

    public function createFromProperty(Property $property): ClassConst
    {
        $propertyProperty = $property->props[0];

        $constantName = $this->constantNaming->createFromProperty($propertyProperty);

        /** @var Expr $defaultValue */
        $defaultValue = $propertyProperty->default;
        $const = new Const_($constantName, $defaultValue);

        $classConst = new ClassConst([$const]);
        $classConst->flags = $property->flags & ~Class_::MODIFIER_STATIC;

        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($property);
        $phpDocInfo->markAsChanged();

        $classConst->setAttribute(AttributeKey::PHP_DOC_INFO, $phpDocInfo);

        return $classConst;
    }
}
