<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\ValueObject\Type;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\RelativeNameNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NativeTypePosition;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

final class NonExistingObjectType extends ObjectType
{
    public function equals(Type $type): bool
    {
        $isEqual = parent::equals($type);

        if ($isEqual) {
            return true;
        }

        if ($type instanceof self || $type::class === ObjectType::class) {
            return $type->getClassName() === $this->getClassName();
        }

        return false;
    }

    public function toNativePhpTypeNode(NativeTypePosition $position): Identifier|Name|ComplexType|null
    {
        return null;
    }

    /**
     * printed as resolved by Rector, which keeps names written with a namespace as they are
     */
    public function toPhpDocNode(): TypeNode
    {
        return new RelativeNameNode($this->getClassName());
    }
}
